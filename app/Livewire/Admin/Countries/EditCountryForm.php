<?php

namespace App\Livewire\Admin\Countries;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class EditCountryForm extends Component
{
    public $country_id;

    // validation rules
    public function rules()
    {
        return [
            'name.ar' => 'required|string|max:30',
            'name.en' => 'nullable|string|max:30',
        ];
    }

    // Run Once only in the beginning
    public function mount()
    {
        $this->country = Country::findOrFail($this->country_id);
        $this->name = [
            'ar' => $this->country->getTranslation('name', 'ar'),
            'en' => $this->country->getTranslation('name', 'en'),
        ];
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.countries.edit-country-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        return $this->validateOnly($field);
    }

    // Commit the update
    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->country->update([
                "name" => [
                    "ar" => $this->name['ar'],
                    "en" => $this->name['en'] != null ? $this->name['en'] : $this->name['ar']
                ]
            ]);

            DB::commit();

            Session::flash('success', __('admin/deliveriesPages.Country updated successfully'));
            redirect()->route('admin.countries.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.Country hasn't been updated"));
            redirect()->route('admin.countries.index');
        }
    }
}
