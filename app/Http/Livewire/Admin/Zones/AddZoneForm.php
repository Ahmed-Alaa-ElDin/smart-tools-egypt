<?php

namespace App\Http\Livewire\Admin\Zones;

use App\Models\Delivery;
use Livewire\Component;

class AddZoneForm extends Component
{
    public $delivery_id, $delivery, $countries, $governorates, $cities, $zones = [
        0 => [
            'name' => "",
            'min_size' => 5,
            'min_charge' => 10.5,
            'kg_charge' => 2,
            'is_active' => 1,
            'addresses' => [
                'country' => [
                    'name' => 'Egypt',
                    'governorate' => [
                        'name' => 'Cairo',
                        'cities' => [
                            'name' => 'Nasr City'
                        ]
                    ]
                ]
            ]
        ]
    ];

    public function mount()
    {
        $this->delivery = Delivery::with('zones')->findOrFail($this->delivery_id);

        $this->name = [
            'ar' => $this->delivery->getTranslation('name', 'ar'),
            'en' => $this->delivery->getTranslation('name', 'en')
        ];
    }


    public function render()
    {
        return view('livewire.admin.zones.add-zone-form');
    }
}
