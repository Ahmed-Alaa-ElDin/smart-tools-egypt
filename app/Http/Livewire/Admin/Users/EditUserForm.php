<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpParser\Node\Stmt\Return_;
use Spatie\Permission\Models\Role;

class EditUserForm extends Component
{
    use WithFileUploads;

    public $user_id;
    public $user;
    public $roles;
    public $photo;
    public $temp_path;
    public $image_name;

    public $defaultPhone = 0;
    public $defaultAddress = 0;

    public $f_name = ['ar' => '', 'en' => ''], $l_name = ['ar' => '', 'en' => ''], $email, $phones = [], $addresses = [], $gender = '0', $role, $birth_date;
    // public $country, $governorate, $city, $details, $special_marque;

    public $countries = [], $governorates = [],  $cities = [];

    protected $listeners = ['countryUpdated', 'governorateUpdated'];

    protected $rules = [
        'f_name.ar' => 'required|string|max:20|min:3',
        'f_name.en' => 'nullable|string|max:20|min:3',
        'l_name.ar' => 'required|string|max:20|min:3',
        'l_name.en' => 'nullable|string|max:20|min:3',
        'email' => 'nullable|required_if:role,2|required_without:phone|email|max:50|min:3|unique:users',
        'phones.*.phone' => 'nullable|required_without:email|digits_between:8,11|unique:phones,phone',
        'gender' => 'in:0,1',
        'role' => 'exists:roles,id',
        'birth_date' => 'date|before:today',
        'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        'addresses.*.country_id'   => 'required|exists:countries,id',
        'addresses.*.governorate_id'    => 'required|exists:governorates,id',
        'addresses.*.city_id'           => 'required|exists:cities,id',
    ];

    public function messages()
    {
        return [
            'phones.*.phone.digits_between' => __('validation.The phone numbers must contain digits between 8 & 11'),
        ];
    }

    // Called Once at the beginning
    public function mount($user_id)
    {
        $this->user_id = $user_id;

        // All Roles
        $this->roles = Role::get();

        // Set User Data
        $this->user = User::with('phones')->with('addresses')->findOrFail($this->user_id);

        // First name
        $this->f_name = [
            'ar' => $this->user->getTranslation('f_name', 'ar'),
            'en' => $this->user->getTranslation('f_name', 'en')
        ];

        // Last name
        $this->l_name = [
            'ar' => $this->user->getTranslation('l_name', 'ar') ?? '',
            'en' => $this->user->getTranslation('l_name', 'en') ?? ''
        ];

        // Email
        $this->email = $this->user->email ?? '';

        // Phones
        $this->phones = $this->user->phones->toArray() ?? [];

        // set default phone number
        $this->defaultPhone = key(array_filter($this->phones, function ($phone) {
            return $phone['default'] == 1;
        }));

        // Gender
        $this->gender = $this->user->gender ?? 0;

        // Role
        $this->role = $this->user->roles->first()->id ?? '';

        // Birth date
        $this->birth_date = $this->user->birth_date ?? '';

        // get user addresses if present
        $this->addresses = $this->user->addresses->toArray() ?? [];

        // all Address data if no addresses
        $this->countries = Country::get();

        if ($this->countries->count() && count($this->addresses) == 0) {
            $this->country = $this->countries->first()->id;
            $this->governorates = Governorate::where('country_id', $this->country)->orderBy('name')->get();

            if ($this->governorates->count()) {
                $this->governorate = $this->governorates->first()->id;
                $this->cities = City::where('governorate_id', $this->governorate)->orderBy('name')->get();
            }
        } elseif (count($this->addresses)) {
            // User Has Addresses
            foreach ($this->addresses as $index => $address) {
                $this->governorates[$index] = Governorate::where('country_id', $address['country_id'])->get()->toArray();
                $this->cities[$index] = City::where('governorate_id', $address['governorate_id'])->get()->toArray();
            }
            $this->defaultAddress = key(array_filter($this->addresses, function ($address) {
                return $address['default'] == 1;
            }));
        }
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.users.edit-user-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    ################ Phones #####################
    public function addPhone()
    {
        array_push($this->phones, [
            "phone" => '',
            'default' => 0
        ]);
    }

    public function removePhone($index)
    {
        unset($this->phones[$index]);

        if ($index == $this->defaultPhone) {
            $this->defaultPhone = array_key_first($this->phones);
        }
    }
    ################ Phones #####################


    ################ Addresses #####################
    public function countryUpdated()
    {
        foreach ($this->addresses as $index => $address) {
            $this->governorates[$index] = Governorate::where('country_id', $address['country_id'])->get()->toArray();
            $this->addresses[$index]['governorate_id'] = $this->governorates[$index] ? $this->governorates[$index][0]['id'] : '';
            $this->cities[$index] = $this->governorates[$index] ? City::where('governorate_id', $this->addresses[$index]['governorate_id'])->get()->toArray() : [];
            $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
        }
    }

    public function governorateUpdated()
    {
        foreach ($this->addresses as $index => $address) {
            $this->cities[$index] = $this->governorates[$index] ? City::where('governorate_id', $this->addresses[$index]['governorate_id'])->get()->toArray() : [];
            $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
        }
    }

    public function addAddress()
    {
        $newAddress = [
            'user_id' => $this->user_id,
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => '',
            'special_marque' => '',
            'default' => 0
        ];

        array_push($this->addresses, $newAddress);

        $governorates = Governorate::where('country_id', 1)->get()->toArray();

        array_push($this->governorates, $governorates);

        array_push($this->cities, City::where('governorate_id', 1)->get()->toArray());
    }


    public function removeAddress($index)
    {
        unset($this->addresses[$index]);

        if ($index == $this->defaultAddress) {
            $this->defaultAddress = array_key_first($this->addresses);
        }
    }
    ################ Addresses #####################
}
