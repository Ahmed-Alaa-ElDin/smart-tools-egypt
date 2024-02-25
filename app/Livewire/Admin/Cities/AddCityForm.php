<?php

namespace App\Livewire\Admin\Cities;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AddCityForm extends Component
{
    public $name = [
        'ar' => '',
        'en' => ''
    ], $country_id, $governorate_id, $governorates;

    // validation rules
    public function rules()
    {
        return [
            'name.ar' => 'required|string|max:30',
            'name.en' => 'nullable|string|max:30',
            'country_id' => 'required|numeric|exists:countries,id',
            'governorate_id' => 'required|numeric|exists:governorates,id',
        ];
    }

    // Run at the beginning
    public function mount()
    {
        $this->countries = Country::get();
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.cities.add-city-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        return $this->validateOnly($field);
    }

    // load country's Governorates
    public function updatedCountryId($country_id)
    {
        $this->governorates = Governorate::where('country_id' , $country_id)->get();
        $this->governorate_id = null;
    }

    // Commit the update
    public function save($new = false)
    {
        $this->validate();

        try {
            DB::beginTransaction();

            City::create([
                "name" => [
                    "ar" => $this->name['ar'],
                    "en" => $this->name['en'] != null ? $this->name['en'] : $this->name['ar']
                ],
                "governorate_id" => $this->governorate_id
            ]);

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/deliveriesPages.City Created successfully'));
                redirect()->route('admin.cities.create');
            } else {
                Session::flash('success', __('admin/deliveriesPages.City Created successfully'));
                redirect()->route('admin.cities.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.City hasn't been Created"));
            redirect()->route('admin.cities.index');
        }
    }
}
