<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Models\Country;
use App\Models\User;
use Livewire\Component;

class NewOrderUserPart extends Component
{
    public $search = "";

    public $customer_id, $selectedCustomer;

    public $addAddress = false;

    protected $listeners = ['clearSearch'];

    public function mount()
    {
        $this->customers = collect([]);

        $this->countries = Country::with([
            'governorates' => fn ($q) => $q->with([
                'cities'
            ])
        ])->get();
    }

    public function render()
    {
        return view('livewire.admin.orders.new-order-user-part');
    }

    public function updatedSearch()
    {
        if ($this->search != "") {
            $this->customers = User::where(function ($q) {
                $q->where('f_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('f_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('phones', function ($q) {
                        $q->where('phone', 'like', '%' . $this->search . '%');
                    });
            })
                ->get();
        } else {
            $this->customers = collect([]);
        }
    }

    public function updatedCustomerId()
    {
        $this->selectedCustomer = User::findOrFail($this->customer_id);

        $this->addAddress = false;

        $this->clearSearch();

        $this->render();
    }

    public function updatedAddAddress($value)
    {
        if ($value) {
            $this->newAddress = [
            'country_id'        => $this->countries->first()->id,
            'governorates'      => $this->countries->first()->governorates,
            'governorate_id'    => $this->countries->first()->governorates->first()->id,
            'cities'            => $this->countries->first()->governorates->first()->cities,
            'city_id'           => $this->countries->first()->governorates->first()->cities->first()->id,
        ];
        }
    }

    public function clearSearch()
    {
        $this->search = null;
        $this->customers = collect([]);
    }

    public function clearCustomer()
    {
        $this->customer_id = null;
        $this->selectedCustomer = null;
    }

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
    }

    public function removeAddress($address_id)
    {
        $this->selectedCustomer->addresses->where('id', $address_id)->first()->delete();
    }

    public function cancelNewAddress()
    {
        $this->newAddress = [
            'country_id'        => $this->countries->first()->id,
            'governorates'      => $this->countries->first()->governorates,
            'governorate_id'    => $this->countries->first()->governorates->first()->id,
            'cities'            => $this->countries->first()->governorates->first()->cities,
            'city_id'           => $this->countries->first()->governorates->first()->cities->first()->id,
        ];

        $this->addAddress = false;
    }

    public function updatedNewAddressCountryId($value)
    {
        $this->newAddress['governorates'] = $this->countries->where('id', $value)->first()->governorates;
        $this->newAddress['governorate_id'] = $this->countries->where('id', $value)->first()->governorates->first()->id;
        $this->newAddress['cities'] = $this->countries->where('id', $value)->first()->governorates->first()->cities;
        $this->newAddress['city_id'] = $this->countries->where('id', $value)->first()->governorates->first()->cities->first()->id;
    }
}
