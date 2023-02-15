<?php

namespace App\Http\Livewire\Admin\Governorates;

use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class EditGovernorateForm extends Component
{
    public $name = [
        'ar' => '',
        'en' => ''
    ], $country_id, $governorate_id;

    // validation rules
    public function rules()
    {
        return [
            'name.ar' => 'required|string|max:30',
            'name.en' => 'nullable|string|max:30',
            'country_id' => 'required|numeric|exists:countries,id',
        ];
    }

    // Run at the beginning
    public function mount()
    {
        $this->countries = Country::get();

        $this->governorate = Governorate::findOrFail($this->governorate_id);

        $this->country_id = $this->governorate->country_id;

        $this->name = [
            'ar' => $this->governorate->getTranslation('name', 'ar'),
            'en' => $this->governorate->getTranslation('name', 'en')
        ];
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.governorates.edit-governorate-form');
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

            $this->governorate->update([
                "name" => [
                    "ar" => $this->name['ar'],
                    "en" => $this->name['en'] != null ? $this->name['en'] : $this->name['ar']
                ],
                "country_id" => $this->country_id
            ]);

            DB::commit();


            Session::flash('success', __('admin/deliveriesPages.Governorate Updated successfully'));
            redirect()->route('admin.governorates.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.Governorate hasn't been Updated"));
            redirect()->route('admin.governorates.index');
        }
    }
}
