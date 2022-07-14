<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\User;
use Livewire\Component;

class OrderShippingDetails extends Component
{
    public $addresses;
    public $changeAddress;

    public $address = [
        'country_id' => 1,
        'governorate_id' => 1,
        'city_id' => 1,
        'details' => null,
        'special_marque' => null,
    ];


    public $countries = [], $governorates = [], $cities = [];
    public $country = null, $governorate = null, $city = null;

    // Validation Rules
    public function rules()
    {
        return [
            'address.country_id'        => 'required|exists:countries,id',
            'address.governorate_id'    => 'required|exists:governorates,id',
            'address.city_id'           => 'required|exists:cities,id',
        ];
    }

    public function mount()
    {
        $this->resetAddress();
    }

    public function render()
    {
        $this->addresses = collect([]);

        if (auth()->user()) {
            $this->user = User::with(['phones', 'addresses' => fn ($q) => $q->with(['country', 'governorate', 'city'])])->findOrFail(auth()->user()->id);

            if ($this->user->addresses->count()) {
                $this->addresses =  $this->user->addresses;
            } else {
                $this->addresses = collect([]);
                $this->changeAddress = true;
            }
        }
        return view('livewire.front.order.order-shipping-details');
    }

    public function selectAddress($address_id)
    {
        // dd($this->user->addresses->where('default', 1)->first()->id == $address_id);
        if ($this->user->addresses->where('default', 1)->count() && $this->user->addresses->where('default', 1)->first()->id != $address_id) {
            // remove default from old address
            $this->user->addresses->where('default', 1)->first()->update(['default' => 0]);
            // set default to new address
            $this->user->addresses->where('id', $address_id)->first()->update(['default' => 1]);
        } else {
            // set default to new address
            $this->user->addresses->where('id', $address_id)->first()->update(['default' => 1]);
        }
    }

    public function addAddress()
    {
        $this->changeAddress = true;
    }

    ################ Address :: Start #####################
    public function updatedAddressCountryId()
    {
        $this->governorates = Governorate::where('country_id', $this->address['country_id'])->orderBy('name->' . session('locale'))->get()->toArray();
        $this->address['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';
        $this->cities = count($this->governorates) ? City::where('governorate_id', $this->address['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray() : [];
        $this->address['city_id'] = $this->cities ? $this->cities[0]['id'] : '';
    }

    public function updatedAddressGovernorateId()
    {
        $this->cities = City::where('governorate_id', $this->address['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray();
        $this->address['city_id'] = $this->cities ? $this->cities[0]['id'] : '';
    }
    ################ Address :: End #####################

    public function save($default)
    {
        $this->validate();

        $this->user->addresses()->create([
            'country_id' => $this->address['country_id'],
            'governorate_id' => $this->address['governorate_id'],
            'city_id' => $this->address['city_id'],
            'details' => $this->address['details'],
            'special_marque' => $this->address['special_marque'],
            'default' => $default,
        ]);

        $this->changeAddress = false;

        $this->resetAddress();
    }

    public function cancel()
    {
        $this->changeAddress = false;

        $this->resetAddress();
    }

    public function resetAddress()
    {
        $this->address = [
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => null,
            'special_marque' => null,
        ];

        $this->countries = Country::orderBy('name->' . session('locale'))->get();

        if ($this->countries->count()) {
            // User Has Addresses
            $this->governorates = Governorate::where('country_id', $this->address['country_id'])->orderBy('name->' . session('locale'))->get()->toArray();
            $this->cities = City::where('governorate_id', $this->address['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray();
        }
    }

    public function removeAddress($address_id)
    {
        $this->user->addresses->where('id', $address_id)->first()->delete();
    }
}
