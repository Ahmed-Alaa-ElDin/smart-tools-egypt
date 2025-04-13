<?php

namespace App\Livewire\Front\Order;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\Phone;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RegisterFromOrder extends Component
{
    public $f_name;
    public $l_name;
    public $phone;
    public $email;
    public $password;
    public $password_confirmation;
    public $address = [
        'country_id' => null,
        'governorate_id' => null,
        'city_id' => null,
        'details' => null,
        'landmarks' => null,
    ];
    public $countries = [];
    public $governorates = [];
    public $cities = [];
    public $country = null;
    public $governorate = null;
    public $city = null;
    public $cart;

    // Validation Rules
    public function rules()
    {
        return [
            'f_name'                    => 'required|string|max:40|min:3',
            'l_name'                    => 'required|string|max:40|min:3',
            'phone'                     => 'required|digits:11|regex:/^01[0-2,5]\d{1,8}$/|' . Rule::unique('phones'),
            'email'                     => 'nullable|email|max:50|min:3',
            'password'                  => 'nullable|string|confirmed|max:50|min:8',
            'address.country_id'        => 'required|exists:countries,id',
            'address.governorate_id'    => 'required|exists:governorates,id',
            'address.city_id'           => 'required|exists:cities,id',
            'address.details'           => 'required|string',
            'address.landmarks'         => 'nullable|string',
        ];
    }

    // Validation Custom messages
    public function messages()
    {
        return [
            'phones.*.phone.digits_between' => __('validation.The phone numbers must contain digits between 8 & 11'),
            'phones.*.phone.regex:/^01[0-2,5]\d{1,8}$/' => __('validation.The phone numbers must start with 010, 011, 012 or 015'),
        ];
    }

    public function mount()
    {
        // get all countries
        $this->countries = Country::get()->toArray();

        if (count($this->countries)) {
            $this->address['country_id'] = $this->countries[0]['id'];

            // User Has Addresses
            $this->governorates = Governorate::where('country_id', $this->address['country_id'])
                ->whereHas('deliveries')
                ->orderBy('name->' . session('locale'))
                ->get()
                ->toArray();
            $this->address['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';

            $this->cities = City::where('governorate_id', $this->address['governorate_id'])
                ->whereHas('deliveries')
                ->orderBy('name->' . session('locale'))
                ->get()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.front.order.register-from-order');
    }


    ################ Address :: Start #####################
    public function updatedAddressCountryId()
    {
        $this->governorates = Governorate::where('country_id', $this->address['country_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();
        $this->address['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';
        $this->cities = count($this->governorates) ? City::where('governorate_id', $this->address['governorate_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray() : [];
        $this->address['city_id'] = $this->cities ? $this->cities[0]['id'] : '';
    }

    public function updatedAddressGovernorateId()
    {
        $this->cities = City::where('governorate_id', $this->address['governorate_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();
        $this->address['city_id'] = $this->cities ? $this->cities[0]['id'] : '';
    }
    ################ Address :: End #####################

    ################ Submit :: End #####################
    public function submit()
    {
        $this->validate();

        $user = User::create([
            'f_name' => [
                "ar" => $this->f_name,
                "en" => $this->f_name,
            ],
            'l_name' => [
                "ar" => $this->l_name,
                "en" => $this->l_name,
            ],
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'last_visit_at' => now(),
            'visit_num' => 1,
        ]);

        $user->assignRole('Customer');

        Phone::create([
            'phone' => $this->phone,
            'user_id' => $user->id,
            'default' => 1
        ]);

        $user->addresses()->create([
            'country_id' => $this->address['country_id'],
            'governorate_id' => $this->address['governorate_id'],
            'city_id' => $this->address['city_id'],
            'details' => $this->address['details'],
            'landmarks' => $this->address['landmarks'],
            'default' => 1,
        ]);

        ############ Restore Cart Data :: Start ############
        $this->cart = Cart::instance('cart')->store($user->id);
        $this->cart = Cart::instance('wishlist')->store($user->id);
        $this->cart = Cart::instance('compare')->store($user->id);
        ############ Restore Cart Data :: End ############

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('front.order.shipping');
    }
}
