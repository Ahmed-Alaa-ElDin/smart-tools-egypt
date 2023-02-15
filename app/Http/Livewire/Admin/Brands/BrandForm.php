<?php

namespace App\Http\Livewire\Admin\Brands;

use App\Models\Brand;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class BrandForm extends Component
{
    use WithFileUploads;

    public $brand_id;
    public $logo,  $logo_name, $deletedImages = [];
    public $name, $country_id;
    public $title, $description_seo;

    protected $listeners = ["descriptionSeo"];

    public function rules()
    {
        return [
            'logo'              =>        'nullable|mimes:jpg,jpeg,png|max:2048',
            'name'              =>        'required|string|max:100|min:3',
            'country_id'        =>        'required|exists:countries,id',
            'title'             =>        'nullable|string|max:100|min:3',
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        $this->countries = Country::get();

        if ($this->brand_id) {

            // Get Old Brand's data
            $brand = Brand::findOrFail($this->brand_id);

            $this->brand = $brand;

            // Assign Old Brand Data
            $this->name = $brand->name;
            $this->country_id = $brand->country_id;
            $this->title = $brand->meta_title;
            $this->description_seo = $brand->meta_description;
            $this->logo_name = $brand->logo_path;
        }
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.brands.brand-form');
    }

    ######################## Logo Image : Start ############################
    // validate and upload photo
    public function updatedLogo($logo)
    {
        $this->validateOnly($logo);

        // Crop and resize photo
        try {
            $this->logo_name = singleImageUpload($logo, 'logo-', 'logos');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteLogo()
    {
        $this->deletedImages[] = $this->logo_name;

        $this->logo = null;
        $this->logo_name = null;
    }
    ######################## Logo Image : Start ############################


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


    ######################## Save New Brand : Start ############################
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $product = Brand::create([
                'name' => $this->name,
                'logo_path' => $this->logo_name ?? null,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
                'country_id' => $this->country_id,
            ]);

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/productsPages.Brand added successfully'));
                redirect()->route('admin.brands.create');
            } else {
                Session::flash('success', __('admin/productsPages.Brand added successfully'));
                redirect()->route('admin.brands.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/productsPages.Brand hasn't been added"));
            redirect()->route('admin.brands.index');
        }
    }
    ######################## Save New Brand : End ############################

    ######################## Save Updated Brand : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->brand->update([
                'name' => $this->name,
                'logo_path' => $this->logo_name ?? null,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
                'country_id' => $this->country_id,
            ]);

            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'brands');
            }

            DB::commit();

            Session::flash('success', __('admin/productsPages.Brand updated successfully'));
            redirect()->route('admin.brands.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/productsPages.Brand hasn't been updated"));
            redirect()->route('admin.brands.index');
        }
    }
    ######################## Save Updated Brand : End ############################

}
