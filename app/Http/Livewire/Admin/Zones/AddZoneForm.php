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
    public $delivery_id, $delivery, $countries, $governorates, $cities, $zones = [
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
                    'governorates' => [],
                    'governorate_id' => '',
                    'allCities' => [],
                    'cities' => []
                ],
            ],
            'max' => 0
        ]
    ];


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

    public function mount()
    {
        $this->delivery = Delivery::with('zones')->findOrFail($this->delivery_id);

        $this->name = [
            'ar' => $this->delivery->getTranslation('name', 'ar'),
            'en' => $this->delivery->getTranslation('name', 'en')
        ];

        $this->countries = Country::get()->toArray();

        foreach ($this->zones as $zone_i => $zone) {
            foreach ($zone['destinations'] as $des_i => $destination) {
                if ($destination['country_id']) {
                    $country_id = $destination['country_id'];
                    $this->zones[$zone_i]['destinations'][$des_i]['governorates'] = Governorate::where('country_id', $country_id)->orderBy('name->' . session('locale'))->get()->toArray();
                }

                if ($destination['governorate_id']) {
                    $governorate_id = $destination['governorate_id'];

                    $this->zones[$zone_i]['destinations'][$des_i]['allCities'] = City::where('governorate_id', $governorate_id)->orderBy('name->' . session('locale'))->get()->toArray();
                }
            }
        }
    }


    public function render()
    {
        return view('livewire.admin.zones.add-zone-form');
    }

    public function activate($zone_index)
    {
        $this->zones[$zone_index]['is_active'] = $this->zones[$zone_index]['is_active'] ? 0 : 1;
    }

    public function maximize($zone_index)
    {
        $this->zones[$zone_index]['max'] = $this->zones[$zone_index]['max'] ? 0 : 1;
    }

    public function removeZone($zone_index)
    {
        unset($this->zones[$zone_index]);
    }

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
                'max' => 0
            ]
        );
    }

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

    public function selectAll($zone_index, $des_index)
    {
        array_map(function ($value) use ($zone_index, $des_index) {
            array_push($this->zones[$zone_index]['destinations'][$des_index]['cities'], $value['id']);
        }, $this->zones[$zone_index]['destinations'][$des_index]['allCities']);
    }

    public function deselectAll($zone_index, $des_index)
    {
        $this->zones[$zone_index]['destinations'][$des_index]['cities'] = [];
    }

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

    public function removeDestination($zone_index, $des_index)
    {
        unset($this->zones[$zone_index]['destinations'][$des_index]);
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

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
