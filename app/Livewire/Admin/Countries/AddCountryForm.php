<?php

namespace App\Livewire\Admin\Countries;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AddCountryForm extends Component
{
    public $name = [
        'ar' => '',
        'en' => ''
    ];

    // validation rules
    public function rules()
    {
        return [
            'name.ar' => 'required|string|max:30',
            'name.en' => 'nullable|string|max:30',
        ];
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.countries.add-country-form');
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

            Country::create([
                "name" => [
                    "ar" => $this->name['ar'],
                    "en" => $this->name['en'] != null ? $this->name['en']: $this->name['ar']
                ]
            ]);

            DB::commit();


            if ($new) {
                Session::flash('success', __('admin/deliveriesPages.Country Created successfully'));
                redirect()->route('admin.countries.create');
            } else {
                Session::flash('success', __('admin/deliveriesPages.Country Created successfully'));
                redirect()->route('admin.countries.index');
                }

        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.Country hasn't been Created"));
            redirect()->route('admin.countries.index');
        }
    }
}
