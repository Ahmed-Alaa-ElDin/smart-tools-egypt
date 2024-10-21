<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoryForm extends Component
{
    use WithFileUploads;

    public $category_id;
    public $name = ['ar' => '', 'en' => ''], $supercategory_id;
    public $title, $description_seo;

    public $supercategories;
    public $category;

    public $image;
    public $image_name;
    public $deletedImages = [];

    protected $listeners = ["descriptionSeo"];

    public function rules()
    {
        return [
            'image'             =>      'nullable|mimes:jpg,jpeg,png|max:2048',
            'name.ar'           =>        'required|string|max:100|min:3',
            'name.en'           =>        'required|string|max:100|min:3',
            'supercategory_id'  =>        'required|exists:supercategories,id',
            'title'             =>        'nullable|string|max:100|min:3',
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        // get all supercategories
        $this->supercategories = Supercategory::select('id', 'name')->get();

        if ($this->category_id) {

            // Get Old Category's data
            $category = Category::with('images')->findOrFail($this->category_id);

            $this->category = $category;

            // Assign Old Category Data
            $this->name = [
                'ar' => $category->getTranslation('name', 'ar'),
                'en' => $category->getTranslation('name', 'en')
            ];
            $this->supercategory_id = $category->supercategory_id;
            $this->title = $category->meta_title;
            $this->description_seo = $category->meta_description;
            $this->image_name = $category->images->count() ? $category->images->first()->file_name : null;
        }
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.categories.category-form');
    }

    ######################## Real Time Validation : Start ############################
    public function updated($field)
    {
        $this->validateOnly($field);
    }
    ######################## Real Time Validation : End ############################

    ######################## Image : Start ############################
    // validate and upload photo
    public function updatedImage($image)
    {
        // Crop and resize photo
        try {
            $this->image_name = singleImageUpload($image, 'category-', 'categories');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteImage()
    {
        $this->deletedImages[] = $this->image_name;

        $this->image = null;
        $this->image_name = null;
    }
    ######################## Image : Start ############################

    ######################## Updated SEO description : Start ############################
    public function descriptionSeo($value)
    {
        $this->description_seo = $value;
    }
    ######################## Updated SEO description : End ############################


    ######################## Save New Category : Start ############################
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $category = Category::create([
                'name' => ['ar' => $this->name['ar'], 'en' => $this->name['en']],
                'supercategory_id' => $this->supercategory_id,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
            ]);

            $category->images()->delete();

            if ($this->image_name != null) {
                $category->images()->create([
                    'file_name' => $this->image_name,
                    'is_thumbnail' => 0,
                    'featured' => 1,
                ]);
            }

            DB::commit();

            foreach ($this->deletedImages as $deletedImage) {
                imageDelete($deletedImage, 'categories');
            }

            if ($new) {
                Session::flash('success', __('admin/productsPages.Category added successfully'));
                redirect()->route('admin.categories.create');
            } else {
                Session::flash('success', __('admin/productsPages.Category added successfully'));
                redirect()->route('admin.categories.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/productsPages.Category has not been added"));
            redirect()->route('admin.categories.index');
        }
    }
    ######################## Save New Category : End ############################

    ######################## Save Updated Category : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->category->update([
                'name' => ['ar' => $this->name['ar'], 'en' => $this->name['en']],
                'supercategory_id' => $this->supercategory_id,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
            ]);

            $this->category->images()->delete();

            if ($this->image_name != null) {
                $this->category->images()->create([
                    'file_name' => $this->image_name,
                    'is_thumbnail' => 0,
                    'featured' => 1,
                ]);
            }

            DB::commit();

            foreach ($this->deletedImages as $deletedImage) {
                imageDelete($deletedImage, 'categories');
            }

            Session::flash('success', __('admin/productsPages.Category updated successfully'));
            redirect()->route('admin.categories.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            // throw $th;

            Session::flash('error', __("admin/productsPages.Category has not been updated"));
            redirect()->route('admin.categories.index');
        }
    }
    ######################## Save Updated Category : End ############################


}
