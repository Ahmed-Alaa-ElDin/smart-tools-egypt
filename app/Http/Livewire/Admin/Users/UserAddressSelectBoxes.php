<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use Livewire\Component;

class UserAddressSelectBoxes extends Component
{
    public $countries = [], $governorates = [],  $cities = [];

    public $choseCountry, $choseGovernorate, $choseCity, $details, $special_marque;

    protected $rules = [
        'choseCountry'        => 'required|exists:countries,id',
        'choseGovernorate'    => 'required|exists:governorates,id',
        'choseCity'           => 'required|exists:cities,id',
    ];

    // Called Once on load
    public function mount()
    {
        // get all countries
        $this->countries = Country::orderBy('name')->get();

        // Choose first country
        $this->choseCountry = $this->countries->first()->id ?? Null;
        $this->emit('choseCountry', $this->choseCountry);

        // get all governorates
        if ($this->choseCountry) {
            $this->governorates = Governorate::where('country_id', $this->choseCountry)->orderBy('name')->get();
        }

        if ($this->governorates) {
            // Choose first governorate
            $this->choseGovernorate = $this->governorates->first()->id ?? Null;
            $this->emit('choseGovernorate', $this->choseGovernorate);


            // get all cities
            $this->cities = City::where('governorate_id', $this->choseGovernorate)->orderBy('name')->get();
            $this->choseCity = $this->cities->first()->id ?? Null;
            $this->emit('choseCity', $this->choseCity);
        }
    }

    // Call with every update
    public function render()
    {
        return view('livewire.admin.users.user-address-select-boxes');
    }

    // Realtime validation
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    // Call when Choose new Country
    public function updatedChoseCountry()
    {
        $this->governorates = Governorate::where('country_id', $this->choseCountry)->get();

        if (!$this->governorates->count()) {
            $this->cities = [];

            $this->emit('choseGovernorate', Null);
            $this->emit('choseCity', Null);
        } else {
            $this->choseGovernorate = $this->governorates->first()->id;

            $this->cities = City::where('governorate_id', $this->choseGovernorate)->orderBy('name')->get();

            $this->validateOnly($this->choseCountry);

            $this->emit('choseCountry', $this->choseCountry);
            $this->emit('choseGovernorate', $this->choseGovernorate);
        }
    }

    // Call when Choose new Governorate
    public function updatedChoseGovernorate()
    {
        $this->cities = City::where('governorate_id', $this->choseGovernorate)->orderBy('name')->get();

        if (!$this->cities->count()) {
            $this->cities = [];
        } else {
            $this->choseCity = $this->cities->first()->id;
        }

        $this->validateOnly($this->choseGovernorate);

        $this->emit('choseGovernorate', $this->choseGovernorate);
    }

    // Call when Choose new City
    public function updatedChoseCity()
    {
        $this->validateOnly($this->choseCity);

        $this->emit('choseCity', $this->choseCity);
    }

    public function updatedDetails()
    {
        $this->emit('details', $this->details);
    }

    public function updatedSpecialMarque()
    {
        $this->emit('specialMarque', $this->special_marque);
    }
}
