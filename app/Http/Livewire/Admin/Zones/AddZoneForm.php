<?php

namespace App\Http\Livewire\Admin\Zones;

use App\Models\City;
use App\Models\Country;
use App\Models\Delivery;
use App\Models\Destination;
use App\Models\Governorate;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AddZoneForm extends Component
{
    // public Properties
    public $delivery_id, $delivery, $countries, $governorates, $cities, $zones;


    // Validation Rules
    public function rules()
    {
        return [
            'zones.*.name.ar'           => 'required|string|max:50|min:3',
            'zones.*.name.en'           => 'nullable|string|max:50|min:3',
            'zones.*.min_charge'        => 'required|numeric|min:0',
            'zones.*.min_size'          => 'nullable|numeric|min:0',
            'zones.*.kg_charge'         => 'nullable|numeric|min:0',
            'zones.*.destinations.*.country_id'        => 'required|exists:countries,id',
            'zones.*.destinations.*.governorate_id'    => 'required|exists:governorates,id',
            'zones.*.destinations.*.city.*'            => 'required|exists:cities,id',
        ];
    }

    // Run once in the beginning
    public function mount()
    {
        // get all delivery companies
        $this->delivery = Delivery::with('zones')->findOrFail($this->delivery_id);

        // get delivery company name
        $this->name = [
            'ar' => $this->delivery->getTranslation('name', 'ar'),
            'en' => $this->delivery->getTranslation('name', 'en')
        ];

        // get all countries
        $this->countries = Country::get()->toArray();

        // Get all zone for this delivery company
        $zonesQuery = Zone::with('destinations')->where('delivery_id', $this->delivery_id);

        // save zones to zones variable
        $this->zones = $zonesQuery->count() ? $zonesQuery->get() : [
            0 => [
                'name' => [
                    'ar' => '',
                    'en' => ''
                ],
                'min_size' => '',
                'min_charge' => '',
                'kg_charge' => '',
                'is_active' => 1,
                'destinations' => [
                    0 => [
                        'country_id' => 1,
                        'governorates' => Governorate::where('country_id', 1)->get()->toArray(),
                        'governorate_id' => '',
                        'allCities' => [],
                        'cities' => []
                    ],
                ],
                'max' => 1
            ]
        ];

        // run only on update
        if ($zonesQuery->count()) {

            $zones_raw = $zonesQuery->get()->toArray();

            // create temp variable for zones
            $zones = [];

            foreach ($zones_raw as $key0 => $zone_raw) {

                $zones[] = [
                    'name' => [
                        'ar' => $zone_raw['name']['ar'],
                        'en' => $zone_raw['name']['en']
                    ],
                    'min_size' => $zone_raw['min_size'],
                    'min_charge' => $zone_raw['min_charge'],
                    'kg_charge' => $zone_raw['kg_charge'],
                    'is_active' => $zone_raw['is_active'],
                    'destinations' => [],
                    'max' => $key0 ? 0 : 1
                ];

                $destinations_raw = Destination::get()->where('zone_id', $zone_raw['id']);

                $destinations = [];

                $countries_ids = $destinations_raw->groupBy('country_id')->keys();

                foreach ($countries_ids as $key1 => $country_id) {
                    $governorates_raw = $destinations_raw->where('country_id', $country_id);

                    $governorates_ids = $governorates_raw->groupBy('governorate_id')->keys();

                    foreach ($governorates_ids as $key2 => $governorate_id) {

                        $cities = $governorates_raw->where('governorate_id', $governorate_id)->groupBy('city_id')->keys()->toArray();

                        $destinations[] = [
                            'country_id' => $country_id,
                            'governorates' => Governorate::where('country_id', $country_id)->get()->toArray(),
                            'governorate_id' => $governorate_id,
                            'allCities' => City::where('governorate_id', $governorate_id)->get()->toArray(),
                            'cities' => $cities
                        ];
                    }
                }

                $zones[$key0]['destinations'] = $destinations;
            }

            $this->zones = $zones;
        }
    }

    // render after each update
    public function render()
    {
        return view('livewire.admin.zones.add-zone-form');
    }

    // Activate / Deactivate zones
    public function activate($zone_index)
    {
        $this->zones[$zone_index]['is_active'] = $this->zones[$zone_index]['is_active'] ? 0 : 1;
    }

    // Maximize / Minimize zone tab
    public function maximize($zone_index)
    {
        $this->zones[$zone_index]['max'] = $this->zones[$zone_index]['max'] ? 0 : 1;
    }

    // Remove Zone
    public function removeZone($zone_index)
    {
        unset($this->zones[$zone_index]);
    }

    // Add New Zone
    public function addZone()
    {
        array_push(
            $this->zones,
            [
                'name' => [
                    'ar' => '',
                    'en' => ''
                ],
                'min_size' => '',
                'min_charge' => '',
                'kg_charge' => '',
                'is_active' => 1,
                'destinations' => [],
                'max' => 1
            ]
        );
    }

    // run on country change
    public function countryUpdated($zone_index, $des_index)
    {
        $country_id = $this->zones[$zone_index]['destinations'][$des_index]['country_id'];

        $this->zones[$zone_index]['destinations'][$des_index]['governorate_id'] = "";
        $this->zones[$zone_index]['destinations'][$des_index]['cities'] = [];
        $this->zones[$zone_index]['destinations'][$des_index]['allCities'] = [];

        if ($country_id) {
            $this->zones[$zone_index]['destinations'][$des_index]['governorates'] = Governorate::where('country_id', $country_id)->orderBy('name->' . session('locale'))->get()->toArray() ?? [];
        } else {
            $this->zones[$zone_index]['destinations'][$des_index]['governorates'] = [];
        }
    }

    // run on governorate change
    public function governorateUpdated($zone_index, $des_index)
    {
        $governorate_id = $this->zones[$zone_index]['destinations'][$des_index]['governorate_id'];

        $this->zones[$zone_index]['destinations'][$des_index]['cities'] = [];

        if ($governorate_id) {
            $this->zones[$zone_index]['destinations'][$des_index]['allCities'] = City::where('governorate_id', $governorate_id)->orderBy('name->' . session('locale'))->get()->toArray() ?? [];
        } else {
            $this->zones[$zone_index]['destinations'][$des_index]['allCities'] = [];
        }
    }

    // Select all cities
    public function selectAll($zone_index, $des_index)
    {
        array_map(function ($value) use ($zone_index, $des_index) {
            array_push($this->zones[$zone_index]['destinations'][$des_index]['cities'], $value['id']);
        }, $this->zones[$zone_index]['destinations'][$des_index]['allCities']);
    }

    // Deselect all cities
    public function deselectAll($zone_index, $des_index)
    {
        $this->zones[$zone_index]['destinations'][$des_index]['cities'] = [];
    }

    // Add destination
    public function addDestination($zone_index)
    {
        $des_index = array_push(
            $this->zones[$zone_index]['destinations'],
            [
                'country_id' => 1,
                'governorates' => Governorate::where('country_id', 1)->orderBy('name->' . session('locale'))->get()->toArray(),
                'governorate_id' => '',
                'allCities' => [],
                'cities' => []
            ]
        );
    }

    // remove Destination
    public function removeDestination($zone_index, $des_index)
    {
        unset($this->zones[$zone_index]['destinations'][$des_index]);
    }

    // run with every update (real time validation)
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    // Save / Update
    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        $this->delivery->zones()->delete();

        try {
            foreach ($this->zones as $zone_index => $zone) {
                $new_zone = Zone::create([
                    "name" => [
                        'ar' => $zone['name']['ar'],
                        'en' => $zone['name']['en'],
                    ],
                    "delivery_id" => $this->delivery_id,
                    "min_size" => $zone['min_size'],
                    "min_charge" => $zone['min_charge'],
                    "kg_charge" => $zone['kg_charge'],
                    "is_active" => $zone['is_active'],
                ]);

                foreach ($zone['destinations'] as $des_index => $destination) {

                    foreach ($destination['cities'] as $city_index => $city) {
                        Destination::create([
                            'zone_id' => $new_zone->id,
                            'country_id' => $destination['country_id'],
                            'governorate_id' => $destination['governorate_id'],
                            'city_id' => $city,
                        ]);
                    }
                }
            }

            DB::commit();

            Session::flash('success', __('admin/deliveriesPages.Zones have been added successfully'));
            redirect()->route('admin.deliveries.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.Zones haven't been added"));
            redirect()->route('admin.deliveries.index');
        }
    }
}
