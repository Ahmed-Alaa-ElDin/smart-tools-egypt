<?php

namespace App\Livewire\Front\Order\OrderForm;

use Livewire\Component;

use App\Models\Address;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Front\Order\OrderForm\Wrapper;

class AddressSection extends Component
{
    public $addresses;
    public $selected_address_id;
    public $show_add_form = false;

    public $address = [
        'country_id' => null,
        'governorate_id' => null,
        'city_id' => null,
        'details' => '',
        'landmarks' => '',
    ];

    public $countries = [], $governorates = [], $cities = [];

    protected $listeners = ['addressUpdated' => 'mount'];

    public function mount()
    {
        if (Auth::check()) {
            $this->addresses = Auth::user()->addresses()->with(['country', 'governorate', 'city'])->get();
            $default = $this->addresses->where('default', true)->first();
            $this->selected_address_id = $default ? $default->id : null;
        }
        $this->countries = Country::all();
    }

    public function selectAddress($id)
    {
        $this->selected_address_id = $id;
        $this->dispatch('addressSelected', $id)->to(Wrapper::class);
    }

    public function toggleAddForm()
    {
        $this->show_add_form = !$this->show_add_form;
        if ($this->show_add_form) {
            $this->resetAddressForm();
        }
    }

    public function updatedAddressCountryId($value)
    {
        $this->governorates = Governorate::where('country_id', $value)->get();
        $this->address['governorate_id'] = null;
        $this->cities = [];
        $this->address['city_id'] = null;
    }

    public function updatedAddressGovernorateId($value)
    {
        $this->cities = City::where('governorate_id', $value)->get();
        $this->address['city_id'] = null;
    }

    public function saveAddress()
    {
        $this->validate([
            'address.country_id' => 'required',
            'address.governorate_id' => 'required',
            'address.city_id' => 'required',
            'address.details' => 'required',
        ]);

        $newAddress = Auth::user()->addresses()->create(array_merge($this->address, [
            'default' => $this->addresses->isEmpty() ? true : false
        ]));

        $this->show_add_form = false;
        $this->mount();
        $this->selectAddress($newAddress->id);
    }

    private function resetAddressForm()
    {
        $this->address = [
            'country_id' => $this->countries->first()?->id,
            'governorate_id' => null,
            'city_id' => null,
            'details' => '',
            'landmarks' => '',
        ];
        if ($this->address['country_id']) {
            $this->updatedAddressCountryId($this->address['country_id']);
        }
    }

    public function render()
    {
        return view('livewire.front.order.order-form.address-section');
    }
}
