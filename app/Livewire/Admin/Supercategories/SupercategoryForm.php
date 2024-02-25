<?php

namespace App\Livewire\Admin\Supercategories;

use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SupercategoryForm extends Component
{
    public $supercategory;
    public $supercategory_id;
    public $name = ['ar' => '', 'en' => ''], $icon;
    public $title, $description_seo;

    protected $listeners = ["descriptionSeo"];

    public function rules()
    {
        return [
            'name.ar'           =>        'required|string|max:100|min:3',
            'name.en'           =>        'required|string|max:100|min:3',
            'icon'              =>        'nullable|string',
            'title'             =>        'nullable|string|max:100|min:3',
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        if ($this->supercategory_id) {

            // Get Old Supercategory's data
            $supercategory = Supercategory::findOrFail($this->supercategory_id);

            $this->supercategory = $supercategory;

            // Assign Old Supercategory Data
            $this->name = [
                'ar' => $supercategory->getTranslation('name', 'ar'),
                'en' => $supercategory->getTranslation('name', 'en')
            ];
            $this->icon = $supercategory->icon;
            $this->title = $supercategory->meta_title;
            $this->description_seo = $supercategory->meta_description;
        }
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.supercategories.supercategory-form');
    }

    ######################## Real Time Validation : Start ############################
    public function updated($field)
    {
        $this->validateOnly($field);
    }
    ######################## Real Time Validation : End ############################


    ######################## Updated SEO description : Start ############################
    public function descriptionSeo($value)
    {
        $this->description_seo = $value;
    }
    ######################## Updated SEO description : End ############################


    ######################## Save New Supercategory : Start ############################
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            Supercategory::create([
                'name' => ['ar' => $this->name['ar'], 'en' => $this->name['en']],
                'icon'=> $this->icon ?? null,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
            ]);

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/productsPages.Supercategory added successfully'));
                redirect()->route('admin.supercategories.create');
            } else {
                Session::flash('success', __('admin/productsPages.Supercategory added successfully'));
                redirect()->route('admin.supercategories.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Session::flash('error', __("admin/productsPages.Supercategory hasn't been added"));
            redirect()->route('admin.supercategories.index');
        }
    }
    ######################## Save New Supercategory : End ############################

    ######################## Save Updated Supercategory : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->supercategory->update([
                'name' => ['ar' => $this->name['ar'], 'en' => $this->name['en']],
                'icon'=> $this->icon ?? null,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
            ]);

            DB::commit();

            Session::flash('success', __('admin/productsPages.Supercategory updated successfully'));
            redirect()->route('admin.supercategories.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Session::flash('error', __("admin/productsPages.Supercategory hasn't been updated"));
            redirect()->route('admin.supercategories.index');
        }
    }
    ######################## Save Updated Supercategory : End ############################
}
