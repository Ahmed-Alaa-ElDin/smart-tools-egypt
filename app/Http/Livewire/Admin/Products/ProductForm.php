<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public $gallery_images = [], $gallery_temp_paths = [], $old_gallery_images = [], $gallery_images_name = [], $featured = 0;
    public $thumbnail_image, $thumbnail_temp_path, $old_thumbnail_image,  $thumbnail_image_name;
    public $video;
    public $name = [], $subcategory_id, $brand_id, $model, $barcode, $weight, $description = ['ar' => '', 'en' => ''], $publish = true, $refundable = true;
    public $base_price, $discount, $final_price, $points, $free_shipping = false, $reviewing = false, $quantity, $low_stock;
    public $title, $description_seo;


    protected $listeners = ["descriptionAr", "descriptionEn", "descriptionSeo"];

    public function rules()
    {
        return [
            'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'thumbnail_image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'video' => 'nullable|active_url',

            'name.ar'                     => 'required|string|max:100|min:3',
            'name.en'                     => 'nullable|string|max:100|min:3',

            'brand_id' => 'required|exists:brands,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'model' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:200',
            'weight' => 'nullable|numeric|min:0',
            'publish' => 'boolean',
            'refundable' => 'boolean',

            'base_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'points' => 'nullable|integer|min:0',
            'free_shipping' => 'boolean',
            'reviewing' => 'boolean',
            'quantity' => 'nullable|numeric|min:0',
            'low_stock' => 'nullable|numeric|min:0',

            'title'                     => 'nullable|string|max:100|min:3',

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


    ######################## Publish Toggle : Start ############################
    public function publish()
    {
        $this->publish = !$this->publish;
    }
    ######################## Publish Toggle : End ############################


    ######################## Refundable Toggle : Start ############################
    public function refund()
    {
        $this->refundable = !$this->refundable;
    }
    ######################## Refundable Toggle : End ############################


    ######################## Free Shipping Toggle : Start ############################
    public function free_shipping()
    {
        $this->free_shipping = !$this->free_shipping;
    }
    ######################## Free Shipping Toggle : End ############################


    ######################## Reviewing Toggle : Start ############################
    public function reviewing()
    {
        $this->reviewing = !$this->reviewing;
    }
    ######################## Reviewing Toggle : End ############################


    ######################## Gallery Images : Start ############################
    // validate and upload photo
    public function updatedGalleryImages($gallery_images)
    {
        $this->validate([
            'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        // dd($photos);

        foreach ($gallery_images as $key => $gallery_image) {
            $image_name = 'product-' . time() . '-' . rand();
            $this->gallery_images_name[] = $image_name;

            // Crop and resize photo
            try {
                $manager = new ImageManager();

                $manager->make($gallery_image)->encode('webp')->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->crop(200, 200)->save('storage/images/products/cropped200/' . $image_name);

                $manager->make($gallery_image)->encode('webp')->save('storage/images/products/original/' . $image_name);

                // for photo rendering
                $this->gallery_temp_paths[] = $gallery_image->temporaryUrl();
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function setFeatured($key)
    {
        $this->featured = $key;
    }

    // remove image
    public function deleteImage($key)
    {
        unset($this->gallery_images_name[$key]);
        unset($this->gallery_temp_paths[$key]);
        unset($this->gallery_images[$key]);
        unset($this->old_gallery_images[$key]);

        $this->featured = array_key_first($this->gallery_images);
    }

    public function removePhoto()
    {
        $this->gallery_images_name = null;
        $this->gallery_images = null;
        $this->gallery_temp_paths = null;
        $this->old_gallery_images = null;
    }
    ######################## Gallery Images : End ############################

    ######################## Thumbnail Image : Start ############################
    // validate and upload photo
    public function updatedThumbnailImage($thumbnail_image)
    {
        $this->validateOnly($thumbnail_image);

        $image_name = 'product-thumbnail-' . time() . '-' . rand();
        $this->thumbnail_image_name[] = $image_name;

        // Crop and resize photo
        try {
            $manager = new ImageManager();

            $manager->make($thumbnail_image)->encode('webp')->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->crop(200, 200)->save('storage/images/products/cropped200/' . $image_name);

            $manager->make($thumbnail_image)->encode('webp')->save('storage/images/products/original/' . $image_name);

            // for photo rendering
            $this->thumbnail_temp_path = $thumbnail_image->temporaryUrl();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteThumbnail()
    {
        $this->thumbnail_image = null;
        $this->thumbnail_temp_path = null;
        $this->old_thumbnail_image = null;
        $this->thumbnail_image_name = null;
    }
    ######################## Thumbnail Image : Start ############################


    ######################## Real Time Validation : Start ############################
    public function updated($field)
    {
        $this->validateOnly($field);

        if ($field == 'base_price') {
            $this->final_price = round($this->base_price - ($this->base_price * ($this->discount / 100)), 2);
        } elseif ($field == 'discount') {
            $this->final_price = round($this->base_price - ($this->base_price * ($this->discount / 100)), 2);
        } elseif ($field == 'final_price') {
            $this->discount = round((($this->base_price - $this->final_price) / $this->base_price) * 100, 2);
        }
    }
    ######################## Real Time Validation : End ############################


    ######################## Updated Arabic description : Start ############################
    public function descriptionAr($value)
    {
        $this->description['ar'] = $value;
    }
    ######################## Updated Arabic description : End ############################


    ######################## Updated English description : Start ############################
    public function descriptionEn($value)
    {
        $this->description['en'] = $value;
    }
    ######################## Updated English description : End ############################


    ######################## Updated SEO description : Start ############################
    public function descriptionSeo($value)
    {
        $this->description_seo = $value;
    }
    ######################## Updated SEO description : End ############################


    ######################## Updated SEO description : End ############################
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $product = Product::create([]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    ######################## Updated SEO description : End ############################

}
