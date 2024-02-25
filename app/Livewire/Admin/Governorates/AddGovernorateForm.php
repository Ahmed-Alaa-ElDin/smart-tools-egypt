<?php

namespace App\Livewire\Admin\Governorates;

use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AddGovernorateForm extends Component
{
    public $name = [
        'ar' => '',
        'en' => ''
    ], $country_id;

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
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.governorates.add-governorate-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        return $this->validateOnly($field);
    }

    // Commit the update
    public function save($new = false)
    {
        $this->validate();

        try {
            DB::beginTransaction();

            Governorate::create([
                "name" => [
                    "ar" => $this->name['ar'],
                    "en" => $this->name['en'] != null ? $this->name['en'] : $this->name['ar']
                ],
                "country_id" => $this->country_id
            ]);

            DB::commit();


            if ($new) {
                Session::flash('success', __('admin/deliveriesPages.Governorate Created successfully'));
                redirect()->route('admin.governorates.create');
            } else {
                Session::flash('success', __('admin/deliveriesPages.Governorate Created successfully'));
                redirect()->route('admin.governorates.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.Governorate hasn't been Created"));
            redirect()->route('admin.governorates.index');
        }
    }
}
