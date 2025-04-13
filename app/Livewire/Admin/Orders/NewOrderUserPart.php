<?php

namespace App\Livewire\Admin\Orders;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class NewOrderUserPart extends Component
{
    public $search = "";

    public $customer_id, $selectedCustomer;

    public $addAddress = false, $defaultAddress;
    public $addPhone = false, $defaultPhone;

    public $newAddress = [
        'country_id'        => null,
        'governorate_id'    => null,
        'city_id'           => null,
        'details'           => null,
        'landmarks'         => null,
    ];
    public $countries = [], $governorates = [], $cities = [];
    public $newPhone = null;
    public $customers;


    protected $listeners = [
        'clearSearch',
    ];

    public function mount()
    {
        $this->customers = collect([]);

        $this->countries = Country::get()->toArray();

        if (count($this->countries)) {
            $this->newAddress['country_id'] = $this->countries[0]['id'];

            // User Has Addresses
            $this->governorates = Governorate::where('country_id', $this->newAddress['country_id'])
                ->whereHas('deliveries')
                ->orderBy('name->' . session('locale'))
                ->get()
                ->toArray();
            $this->newAddress['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';

            $this->cities = City::where('governorate_id', $this->newAddress['governorate_id'])
                ->whereHas('deliveries')
                ->orderBy('name->' . session('locale'))
                ->get()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.admin.orders.new-order-user-part');
    }

    public function updatedSearch()
    {
        $term = trim($this->search);

        if (blank($term)) {
            $this->customers = collect([]);
            return;
        }

        $this->customers = User::query()
            ->where(function ($query) use ($term) {
                $query->where('f_name->ar', 'like', "%$term%")
                    ->orWhere('l_name->ar', 'like', "%$term%")
                    ->orWhere('f_name->en', 'like', "%$term%")
                    ->orWhere('l_name->en', 'like', "%$term%")
                    ->orWhere('email', 'like', "%$term%")
                    ->orWhereHas('phones', fn($q) => $q->where('phone', 'like', "%$term%"));
            })
            ->limit(10)
            ->get();
    }

    public function clearSearch()
    {
        $this->search = null;
        $this->customers = collect([]);
    }

    public function updatedCustomerId()
    {
        $this->selectedCustomer = User::with('addresses', 'phones')->findOrFail($this->customer_id);

        $defaultAddress = $this->selectedCustomer->addresses->where('default', 1)->first();

        $this->defaultAddress = $defaultAddress ? $defaultAddress->id : null;

        $defaultPhone = $this->selectedCustomer->phones->where('default', 1)->first();

        $this->defaultPhone = $defaultPhone ? $defaultPhone->id : null;

        $this->addAddress = false;

        $this->dispatch('setUserData', [
            'customer' => $this->selectedCustomer,
            'default_address' => $this->defaultAddress,
            'default_phone' => $this->defaultPhone
        ]);

        $this->clearSearch();
    }

    public function clearCustomer()
    {
        $this->customer_id = null;
        $this->selectedCustomer = null;

        $this->dispatch('setUserData', [
            'customer' => $this->selectedCustomer,
            'default_address' => $this->defaultAddress,
            'default_phone' => $this->defaultPhone
        ]);
    }

    ############ Address :: Start ##############
    // Select exist Address
    public function selectAddress($address_id)
    {
        if ($this->selectedCustomer->addresses->where('default', 1)->count() && $this->selectedCustomer->addresses->where('default', 1)->first()->id != $address_id) {
            // remove default from old address
            $this->selectedCustomer->addresses->where('default', 1)->first()->update(['default' => 0]);
            // set default to new address
            $this->selectedCustomer->addresses->where('id', $address_id)->first()->update(['default' => 1]);
        } else {
            // set default to new address
            $this->selectedCustomer->addresses->where('id', $address_id)->first()->update(['default' => 1]);
        }

        $this->defaultAddress = $address_id;

        $this->dispatch('setUserData', [
            'customer' => $this->selectedCustomer,
            'default_address' => $this->defaultAddress,
            'default_phone' => $this->defaultPhone
        ]);
    }

    // Remove exist Address
    public function removeAddress($address_id)
    {
        try {
            $this->selectedCustomer->addresses->where('id', $address_id)->first()->delete();

            $this->selectedCustomer->load('addresses');

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Address has been deleted successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Address has not been deleted"),
                icon: 'error'
            );
        }
    }

    // Add / Cancel New Address
    public function updatedAddAddress($value)
    {
        if ($value) {
            $this->countries = Country::get()->toArray();

            if (count($this->countries)) {
                $this->newAddress['country_id'] = $this->countries[0]['id'];

                $this->governorates = Governorate::where('country_id', $this->newAddress['country_id'])
                    ->orderBy('name->' . session('locale'))
                    ->get()
                    ->toArray();
                $this->newAddress['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';

                $this->cities = City::where('governorate_id', $this->newAddress['governorate_id'])
                    ->orderBy('name->' . session('locale'))
                    ->get()
                    ->toArray();
                $this->newAddress['city_id'] = count($this->cities) ? $this->cities[0]['id'] : '';
            }
        } else {
            $this->newAddress = [
                'country_id'        => null,
                'governorate_id'    => null,
                'city_id'           => null,
                'details'           => null,
                'landmarks'         => null,
            ];
        }
    }

    // Updated country id in new address
    public function updatedNewAddressCountryId($value)
    {
        // Get the Governorates of the selected country
        $this->governorates = Governorate::where('country_id', $value)
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();

        // set default governorate id
        $this->newAddress['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';

        // Get the cities of the selected governorate
        $this->cities = count($this->governorates) ? City::where('governorate_id', $this->newAddress['governorate_id'])->whereHas('deliveries')->orderBy('name->' . session('locale'))->get()->toArray() : [];

        // set default city id
        $this->newAddress['city_id'] = count($this->cities) ? $this->cities[0]['id'] : '';
    }

    // Updated governorate id in new address
    public function updatedNewAddressGovernorateId($value)
    {
        // Get the cities of the selected governorate
        $this->cities = City::where('governorate_id', $value)->whereHas('deliveries')->orderBy('name->' . session('locale'))->get()->toArray();

        // set default city id
        $this->newAddress['city_id'] = count($this->cities) ? $this->cities[0]['id'] : '';
    }

    // save new address
    public function saveAddress()
    {
        $this->validate([
            'newAddress.country_id'        => 'required|exists:countries,id',
            'newAddress.governorate_id'    => 'required|exists:governorates,id',
            'newAddress.city_id'           => 'required|exists:cities,id',
            'newAddress.details'           => 'required|string',
            'newAddress.landmarks'         => 'nullable|string',
        ]);

        try {
            $this->selectedCustomer->addresses()->create([
                'user_id'           =>      $this->customer_id,
                'country_id'        =>      $this->newAddress['country_id'],
                'governorate_id'    =>      $this->newAddress['governorate_id'],
                'city_id'           =>      $this->newAddress['city_id'],
                'details'           =>      $this->newAddress['details'],
                'landmarks'         =>      $this->newAddress['landmarks'],
                'default'           =>      0,
            ]);

            $this->addAddress = false;

            $this->selectedCustomer->load('addresses');

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Address has been created successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Address has not been created"),
                icon: 'error'
            );
        }
    }
    ############ Address :: End ##############

    ############ Phone :: Start ##############
    // Select exist Phone
    public function selectPhone($phone_id)
    {
        if ($this->selectedCustomer->phones->where('default', 1)->count() && $this->selectedCustomer->phones->where('default', 1)->first()->id != $phone_id) {
            // remove default from old phone
            $this->selectedCustomer->phones->where('default', 1)->first()->update(['default' => 0]);
            // set default to new phone
            $this->selectedCustomer->phones->where('id', $phone_id)->first()->update(['default' => 1]);
        } else {
            // set default to new phone
            $this->selectedCustomer->phones->where('id', $phone_id)->first()->update(['default' => 1]);
        }

        $this->defaultPhone = $phone_id;

        $this->dispatch('setUserData', [
            'customer' => $this->selectedCustomer,
            'default_address' => $this->defaultAddress,
            'default_phone' => $this->defaultPhone
        ]);
    }

    // Remove exist Phone
    public function removePhone($phone_id)
    {
        try {
            $this->selectedCustomer->phones->where('id', $phone_id)->first()->delete();

            $this->selectedCustomer->load('phones');

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Phone has been deleted successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Phone has not been deleted"),
                icon: 'error'
            );
        }
    }

    // Add / Cancel New Phone
    public function updatedAddPhone($value)
    {
        if ($value) {
            $this->newPhone = null;
        }
    }

    // save new phone
    public function savePhone()
    {
        $this->validate([
            'newPhone'      =>    'required|digits:11|regex:/^01[0125]\d{1,8}$/|' . Rule::unique('phones', 'phone')->ignore($this->customer_id, 'user_id'),
        ], [
            'newPhone.regex' => __('validation.The phone numbers must start with 010, 011, 012 or 015')
        ]);

        try {
            $this->selectedCustomer->phones()->create([
                'user_id'       =>      $this->customer_id,
                'phone'         =>      $this->newPhone,
                'default'       =>      0,
            ]);

            $this->addPhone = false;

            $this->selectedCustomer->load('phones');

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Phone has been created successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Phone has not been created"),
                icon: 'error'
            );
        }
    }
    ############ Phone :: End ##############

    // public function getUserData()
    // {
    //     $this->dispatch(
    //         'setUserData',
    //         data: [
    //             'customer' => $this->selectedCustomer,
    //             'default_address' => $this->defaultAddress,
    //             'default_phone' => $this->defaultPhone
    //         ]
    //     )->to('admin.orders.order-form');
    // }
}
