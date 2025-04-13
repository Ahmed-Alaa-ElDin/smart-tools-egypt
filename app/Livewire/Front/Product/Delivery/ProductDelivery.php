<?php

namespace App\Livewire\Front\Product\Delivery;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\User;
use App\Models\Zone;
use Livewire\Component;

class ProductDelivery extends Component
{
    public $free_shipping = true, $product_weight;
    public $countries, $selected_country, $selected_country_id;
    public $governorates, $selected_governorate, $selected_governorate_id;
    public $cities, $selected_city, $selected_city_id;
    public $delivery_cost;
    public $user;

    protected $rules = [
        'selected_country_id' => 'required',
        'selected_governorate_id' => 'required',
        'selected_city_id' => 'required',
    ];

    ###### Mount :: START ######
    public function mount()
    {

        if (!$this->free_shipping) {
            if (auth()->check()) {
                $this->user = User::with([
                    'phones',
                    'addresses' => fn ($q) => $q->with(['country', 'governorate', 'city'])
                ])->findOrFail(auth()->user()->id);

                $address = $this->user->addresses->where('default', 1)->first();

                $this->selected_country_id = $address->country_id ?? 1;
                $this->selected_governorate_id = $address->governorate_id ?? 1;
                $this->selected_city_id = $address->city_id ?? 1;

                $this->countries = Country::all();
                $this->governorates = Governorate::where('country_id', $this->selected_country_id)->get();
                $this->cities = City::where('governorate_id', $this->selected_governorate_id)->get();

                $this->selected_country = $this->countries->where('id', $this->selected_country_id)->first();
                $this->selected_governorate = $this->governorates->where('id', $this->selected_governorate_id)->first();
                $this->selected_city = $this->cities->where('id', $this->selected_city_id)->first();
            } else {
                // Get All Countries
                $this->countries = Country::select(['id', 'name'])->with([
                    'governorates' => fn ($q) => $q->select(['id', 'name', 'country_id'])->with([
                        'cities' => fn ($q) => $q->select(['id', 'name', 'governorate_id'])
                    ])
                ])->get() ?? collect([]);

                // Get the Selected Country
                $this->selected_country = $this->countries->first() ?? collect([]);
                $this->selected_country_id = $this->selected_country->id ?? null;

                // Get the Selected Country's Governorates
                if ($this->selected_country) {
                    $this->governorates = $this->selected_country->governorates ?? collect([]);
                    $this->selected_governorate = $this->governorates->first() ?? collect([]);
                    $this->selected_governorate_id = $this->selected_governorate->id ?? null;
                }

                // Get the Selected Governorate's Cities
                if ($this->selected_governorate) {
                    $this->cities = $this->selected_governorate->cities ?? collect([]);
                    $this->selected_city = $this->cities->first() ?? collect([]);
                    $this->selected_city_id = $this->selected_city->id ?? null;
                }
            }
        }
    }
    ###### Mount :: END ######


    ###### Render :: START ######
    public function render()
    {
        return view('livewire.front.product.delivery.product-delivery');
    }
    ###### Render :: END ######

    ###### Updated :: START ######
    public function updated($field)
    {
        $this->validateOnly($field);

        switch ($field) {
            case 'selected_country_id':
                $this->selected_country = $this->countries->find($this->selected_country_id);
                $this->selected_country_id = $this->selected_country->id ?? null;
                $this->selected_governorate = null;
                $this->selected_governorate_id = null;
                $this->selected_city = null;
                $this->selected_city_id = null;
                $this->governorates = $this->selected_country->governorates ?? collect([]);
                $this->cities = collect([]);
                $this->delivery_cost = null;
                break;
            case 'selected_governorate_id':
                $this->selected_governorate = $this->governorates->find($this->selected_governorate_id);
                $this->selected_governorate_id = $this->selected_governorate->id ?? null;
                $this->selected_city = null;
                $this->selected_city_id = null;
                $this->cities = $this->selected_governorate->cities ?? collect([]);
                $this->delivery_cost = null;
                break;
            case 'selected_city_id':
                $this->selected_city = $this->cities->find($this->selected_city_id);
                $this->selected_city_id = $this->selected_city->id ?? null;
                $this->delivery_cost = null;
                break;
        }
    }
    ###### Updated :: END ######

    ###### Calculate the Cost :: START ######
    public function calculate()
    {
        // Validate inputs
        $this->validate();

        // Get All Zones that deliver to the selected city
        $zones = Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn ($q) => $q->where('city_id', $this->selected_city_id))
            ->whereHas('delivery', fn ($q) => $q->where('is_active', 1))
            ->get();

        // Get the best Delivery Cost
        $delivery_cost = $zones->map(function ($zone) {
            $min_charge = $zone->min_charge;
            $min_weight = $zone->min_weight;
            $kg_charge = $zone->kg_charge;
            $product_weight = ceil($this->product_weight);

            if ($product_weight < $min_weight) {
                return $min_charge;
            } else {
                return $min_charge + ($product_weight - $min_weight) * $kg_charge;
            }
        })->min();

        $this->delivery_cost = $delivery_cost ?? 'no delivery';

        // if user has not set his address yet, make this address as default
        if (auth()->check() && $this->user->addresses->count() == 0) {
            $this->user->addresses->create([
                'country_id' => $this->selected_country_id,
                'governorate_id' => $this->selected_governorate_id,
                'city_id' => $this->selected_city_id,
                'default' => 1,
            ]);
        }
    }
}
