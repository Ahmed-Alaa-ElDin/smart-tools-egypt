<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public $gallery_images = [], $temp_path = [], $oldImage;
    public $name = [], $subcategory_id, $brand_id, $publish = true, $refundable = true, $free_shipping = false, $image_name;


    public function rules()
    {
        return [
            // 'f_name.ar' => 'required|string|max:20|min:3',
            // 'f_name.en' => 'nullable|string|max:20|min:3',
            // 'l_name.ar' => 'nullable|string|max:20|min:3',
            // 'l_name.en' => 'nullable|string|max:20|min:3',
            // 'email' => 'nullable|required_if:role,2|required_without:phones.' . $this->defaultPhone . '.phone|email|max:50|min:3|unique:users,email,' . $this->user_id,
            // 'phones.*.phone' => 'nullable|required_without:email|digits_between:8,11|' . Rule::unique('phones')->ignore($this->user_id, 'user_id'),
            // 'gender' => 'in:0,1',
            // 'role' => 'exists:roles,id',
            // 'birth_date' => 'date|before:today',
            'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            // 'addresses.*.country_id'   => 'required|exists:countries,id',
            // 'addresses.*.governorate_id'    => 'required|exists:governorates,id',
            // 'addresses.*.city_id'           => 'required|exists:cities,id',
            // 'defaultAddress'   => 'required',
            // 'defaultPhone'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phones.*.phone.digits_between' => __('validation.The phone numbers must contain digits between 8 & 11'),
            'email.required_if' => __('validation.The Email Address is required when role is admin.'),
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        $this->categories = Category::with('subcategories')->get();

        $this->brands = Brand::get();
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.products.product-form');
    }

    public function publish()
    {
        $this->publish = !$this->publish;
    }

    public function refund()
    {
        $this->refundable = !$this->refundable;
    }

    public function free_shipping()
    {
        $this->free_shipping = !$this->free_shipping;
    }

    ######################## Profile Image : Start ############################
    // validate and upload photo
    public function updatedGalleryImages($gallery_images)
    {
        $this->validate([
            'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        // dd($photos);

        foreach ($gallery_images as $key => $gallery_image) {
            $this->image_name[$key] = 'product-' . time() . '-' . rand();

            // Crop and resize photo
            try {
                $manager = new ImageManager();

                $manager->make($gallery_image)->encode('webp')->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->crop(200, 200)->save('storage/images/products/cropped200/' . $this->image_name[$key]);

                $manager->make($gallery_image)->encode('webp')->save('storage/images/products/original/' . $this->image_name[$key]);

                // for photo rendering
                $this->temp_path[$key] = $gallery_image->temporaryUrl();
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    // remove image
    public function removePhoto()
    {
        $this->image_name = Null;
        $this->temp_path = Null;
        $this->oldImage = Null;
    }
    ######################## Profile Image : End ############################

}
