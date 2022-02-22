<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use Livewire\Component;

class UserAddressSelectBoxes extends Component
{
    public $countries = [], $governorates = [],  $cities = [];

    public $choosedCountry, $choosedGovernorate, $choosedCity, $details, $special_marque;

    protected $rules = [
        'choosedCountry'        => 'required|exists:countries,id',
        'choosedGovernorate'    => 'required|exists:governorates,id',
        'choosedCity'           => 'required|exists:cities,id',
    ];

    // Called Once on load
    public function mount()
    {
        // get all countries
        $this->countries = Country::orderBy('name')->get();

        // Choose first country
        $this->choosedCountry = $this->countries->first()->id ?? Null;
        $this->emit('choosedCountry', $this->choosedCountry);

        // get all governorates
        if ($this->choosedCountry) {
            $this->governorates = Governorate::where('country_id', $this->choosedCountry)->orderBy('name')->get();
        }

        if ($this->governorates) {
            // Choose first governorate
            $this->choosedGovernorate = $this->governorates->first()->id ?? Null;
            $this->emit('choosedGovernorate', $this->choosedGovernorate);


            // get all cities
            $this->cities = City::where('governorate_id', $this->choosedGovernorate)->orderBy('name')->get();
            $this->choosedCity = $this->cities->first()->id ?? Null;
            $this->emit('choosedCity', $this->choosedCity);
        }
    }

    // Call with every update
    public function render()
    {
        return view('livewire.admin.users.user-address-select-boxes');
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    // Call when Choose new Country
    public function updatedChoosedCountry()
    {
        $this->governorates = Governorate::where('country_id', $this->choosedCountry)->get();

        if (!$this->governorates->count()) {
            $this->cities = [];

            $this->emit('choosedGovernorate', Null);
            $this->emit('choosedCity', Null);
        } else {
            $this->choosedGovernorate = $this->governorates->first()->id;

            $this->cities = City::where('governorate_id', $this->choosedGovernorate)->orderBy('name')->get();

            $this->validateOnly($this->choosedCountry);

            $this->emit('choosedCountry', $this->choosedCountry);
            $this->emit('choosedGovernorate', $this->choosedGovernorate);
        }
    }

    // Call when Choose new Governorate
    public function updatedChoosedGovernorate()
    {
        $this->cities = City::where('governorate_id', $this->choosedGovernorate)->orderBy('name')->get();

        if (!$this->cities->count()) {
            $this->cities = [];
        } else {
            $this->choosedCity = $this->cities->first()->id;
        }

        $this->validateOnly($this->choosedGovernorate);

        $this->emit('choosedGovernorate', $this->choosedGovernorate);
    }

    // Call when Choose new City
    public function updatedChoosedCity()
    {
        $this->validateOnly($this->choosedCity);

        $this->emit('choosedCity', $this->choosedCity);
    }
}
