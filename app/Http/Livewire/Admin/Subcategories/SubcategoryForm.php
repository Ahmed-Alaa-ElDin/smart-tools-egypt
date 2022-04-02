<?php

namespace App\Http\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SubcategoryForm extends Component
{
    public $subcategory_id;
    public $name = ['ar' => '', 'en' => ''];
    public $supercategory_id, $category_id;
    public $title, $description_seo;

    protected $listeners = ["descriptionSeo"];

    public function rules()
    {
        return [
            'name.ar'           =>        'required|string|max:100|min:3',
            'name.en'           =>        'required|string|max:100|min:3',
            'supercategory_id'  =>        'required|exists:supercategories,id',
            'category_id'       =>        'required|exists:categories,id',
            'title'             =>        'nullable|string|max:100|min:3',
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        $this->supercategories = Supercategory::select('id', 'name')->get();

        if ($this->subcategory_id) {

            // Get Old Subcategory's data
            $subcategory = Subcategory::with(['category','supercategory'])->findOrFail($this->subcategory_id);

            $this->subcategory = $subcategory;

            // Assign Old Subcategory Data
            $this->name = [
                'ar' => $subcategory->getTranslation('name', 'ar'),
                'en' => $subcategory->getTranslation('name', 'en')
            ];
            $this->supercategory_id = $subcategory->supercategory->id;
            $this->category_id = $subcategory->category->id;
            $this->title = $subcategory->meta_title;
            $this->description_seo = $subcategory->meta_description;
        }
    }

    // Run with every update
    public function render()
    {
        if ($this->supercategories) {
            $this->categories = Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', '=', $this->supercategory_id)->get();
        }

        return view('livewire.admin.subcategories.subcategory-form');
    }

    ######################## Real Time Validation : Start ############################
    public function updated($field)
    {
        $this->validateOnly($field);
    }
    ######################## Real Time Validation : End ############################

    public function updatedSupercategoryId()
    {
        $this->category_id = null;
    }

    ######################## Updated SEO description : Start ############################
    public function descriptionSeo($value)
    {
        $this->description_seo = $value;
    }
    ######################## Updated SEO description : End ############################


    ######################## Save New Subcategory : Start ############################
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            Subcategory::create([
                'name' => ['ar' => $this->name['ar'], 'en' => $this->name['en']],
                'supercategory_id' => $this->supercategory_id,
                'category_id' => $this->category_id,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
            ]);

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/productsPages.Subcategory added successfully'));
                redirect()->route('admin.subcategories.create');
            } else {
                Session::flash('success', __('admin/productsPages.Subcategory added successfully'));
                redirect()->route('admin.subcategories.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Session::flash('error', __("admin/productsPages.Subcategory hasn't been added"));
            redirect()->route('admin.subcategories.index');
        }
    }
    ######################## Save New Subcategory : End ############################

    ######################## Save Updated Subcategory : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->subcategory->update([
                'name' => ['ar' => $this->name['ar'], 'en' => $this->name['en']],
                'supercategory_id' => $this->supercategory_id,
                'category_id' => $this->category_id,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
            ]);

            DB::commit();

            Session::flash('success', __('admin/productsPages.Subcategory updated successfully'));
            redirect()->route('admin.subcategories.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/productsPages.Subcategory hasn't been updated"));
            redirect()->route('admin.subcategories.index');
        }
    }
    ######################## Save Updated Subcategory : End ############################
}
