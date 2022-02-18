<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use Livewire\Component;

class UserAddressSelectBoxes extends Component
{
    public $countries = [], $governorates = [],  $cities = [];

    public $choosedCountry, $choosedGovernorate, $choosedCity;

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
        $this->choosedCountry = $this->countries->first()->id ?? Null;
        if ($this->choosedCountry) {
            $this->governorates = Governorate::where('country_id', $this->choosedCountry)->orderBy('name')->get();
        }
        if ($this->governorates) {
            $this->choosedGovernorate = $this->governorates->first()->id ?? Null;
            $this->cities = City::where('governorate_id', $this->choosedGovernorate)->orderBy('name')->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-address-select-boxes');
    }

    public function updatedChoosedCountry()
    {
        $this->governorates = Governorate::where('country_id', $this->choosedCountry)->get();
        if (!$this->governorates->count()) {
            $this->cities = [];
        } else {
            $this->cities = City::where('governorate_id', $this->choosedGovernorate)->orderBy('name')->get();
        }
    }

    public function updatedChoosedGovernorate()
    {
        $this->cities = City::where('governorate_id', $this->choosedGovernorate)->orderBy('name')->get();
    }
}
