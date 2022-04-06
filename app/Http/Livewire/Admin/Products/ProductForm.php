<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public $product_id;
    public $gallery_images = [], $gallery_images_name = [], $featured = 0, $deletedImages = [];
    public $thumbnail_image,  $thumbnail_image_name;
    public $video;
    public $name = ["ar" => "", "en" => ""], $brand_id, $model, $barcode, $weight, $description = ['ar' => '', 'en' => ''], $publish = true, $refundable = true;
    public $base_price, $discount, $final_price, $points, $free_shipping = false, $reviewing = false, $quantity, $low_stock;
    public $title, $description_seo;
    public $parentCategories;

    protected $listeners = ["descriptionAr", "descriptionEn", "descriptionSeo", "supercategoryUpdated", "categoryUpdated"];

    public function rules()
    {
        return [
            'gallery_images.*'    =>        'nullable|mimes:jpg,jpeg,png|max:2048',
            'thumbnail_image'     =>        'nullable|mimes:jpg,jpeg,png|max:2048',
            'video'               =>        'nullable|active_url',

            'name.ar'             =>        'required|string|max:100|min:3',
            'name.en'             =>        'nullable|string|max:100|min:3',

            'brand_id'            =>        'required|exists:brands,id',
            'parentCategories.*.supercategory_id'     =>        'required|gt:0|exists:supercategories,id',
            'parentCategories.*.category_id'          =>        'required|gt:0|exists:categories,id',
            'parentCategories.*.subcategory_id'       =>        'required|gt:0|exists:subcategories,id',
            'model'               =>        'nullable|string|max:100',
            'barcode'             =>        'nullable|string|max:200',
            'weight'              =>        'nullable|numeric|min:0|max:999999',
            'publish'             =>        'boolean',
            'refundable'          =>        'boolean',

            'base_price'          =>        'required|numeric|min:0|max:999999',
            'discount'            =>        'nullable|numeric|min:0|max:100',
            'final_price'         =>        'nullable|numeric|min:0|max:999999|lte:base_price',
            'points'              =>        'nullable|integer|min:0|max:999999',
            'free_shipping'       =>        'boolean',
            'reviewing'           =>        'boolean',
            'quantity'            =>        'nullable|numeric|min:0',
            'low_stock'           =>        'nullable|numeric|min:0',

            'title'               =>        'nullable|string|max:100|min:3',

        ];
    }

    public function messages()
    {
        return [
            'parentCategories.*.supercategory_id.gt' => __('validation.required'),
            'parentCategories.*.category_id.gt' => __('validation.required'),
            'parentCategories.*.subcategory_id.gt' => __('validation.required'),
        ];
    }

    // Called Once at the beginning
    public function mount()
    {

        $this->brands = Brand::get();

        if ($this->product_id) {

            // Get Old Product's data
            $product = Product::with(['subcategories' => fn ($q) => $q->with(['category' => fn ($q) => $q->with('supercategory')])])->findOrFail($this->product_id);

            $this->product = $product;

            // Old Media
            $products_images = ProductImage::where('product_id', $product->id)->get();

            $this->gallery_images_name = $products_images
                ->where('is_thumbnail', 0)
                ->pluck('file_name')
                ->toArray();

            $this->gallery_images_featured = $products_images
                ->where('is_thumbnail', 0)
                ->pluck('featured')
                ->toArray();

            foreach ($this->gallery_images_featured as $key => $value) {
                if ($value == 1) {
                    $this->featured = $key;
                }
            }

            $this->thumbnail_image_name = $products_images
                ->where('is_thumbnail', 1)
                ->first() ? $products_images->where('is_thumbnail', 1)->first()->file_name : null;

            $this->video = $product->video;

            // Old Product's Info
            $this->name = [
                'ar' => $product->getTranslation('name', 'ar'),
                'en' => $product->getTranslation('name', 'en')
            ];

            // old Subcategories
            if (count($this->product->subcategories)) {
                foreach ($this->product->subcategories as $key => $subcategory) {
                    $this->parentCategories[] = [
                        'supercategories' => Supercategory::select('id', 'name')->get()->toArray(),
                        'supercategory_id' => $subcategory->category->supercategory->id,
                        'categories' => Category::where('supercategory_id', $subcategory->category->supercategory->id)->get()->toArray(),
                        'category_id' => $subcategory->category->id,
                        'subcategories' => Subcategory::where('category_id', $subcategory->category->id)->get()->toArray(),
                        'subcategory_id' => $subcategory->id,
                    ];
                }
            } else {
                $this->parentCategories = [
                    [
                        'supercategory_id' => 0,
                        'supercategories' => null,
                        'category_id' => 0,
                        'categories' => null,
                        'subcategory_id' => 0,
                        'subcategories' => null,
                    ]
                ];
                $this->parentCategories[0]['supercategories'] = Supercategory::select('id', 'name')->get()->toArray();
            }

            $this->brand_id = $product->brand_id;
            $this->model = $product->model;
            $this->barcode = $product->barcode;
            $this->weight = $product->weight;
            $this->description = [
                'ar' => $product->getTranslation('description', 'ar'),
                'en' => $product->getTranslation('description', 'en'),
            ];

            $this->publish = $product->publish;
            $this->refundable = $product->refundable;

            // Old Stock and Price
            $this->base_price = $product->base_price;
            $this->final_price = $product->final_price;
            $this->discount = round((($this->base_price - $this->final_price) / $this->base_price) * 100, 2);
            $this->points = $product->points;
            $this->free_shipping = $product->free_shipping;
            $this->reviewing = $product->under_reviewing;
            $this->quantity = $product->quantity;
            $this->low_stock = $product->low_stock;

            // SEO
            $this->title = $product->meta_title;
            $this->description_seo = $product->meta_description;
        } else {
            $this->parentCategories = [
                [
                    'supercategory_id' => 0,
                    'supercategories' => null,
                    'category_id' => 0,
                    'categories' => null,
                    'subcategory_id' => 0,
                    'subcategories' => null,
                ]
            ];
        }
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

        foreach ($gallery_images as $key => $gallery_image) {
            try {
                $this->gallery_images_name[] = singleImageUpload($gallery_image, 'product-', 'products');
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
        $this->deletedImages[] = $this->gallery_images_name[$key];

        unset($this->gallery_images_name[$key]);
        unset($this->gallery_images[$key]);

        if ($key == $this->featured) {
            $this->featured = array_key_first($this->gallery_images_name);
        }
    }

    public function deleteOldImage($key)
    {
        $this->deletedImages[] = $this->gallery_images_name[$key];

        unset($this->gallery_images_name[$key]);

        if ($key == $this->featured) {
            $this->featured = array_key_first($this->gallery_images_name);
        }
    }

    public function removePhoto()
    {
        array_push($this->deletedImages, ...$this->gallery_images_name);

        $this->gallery_images_name = [];
        $this->gallery_images = [];
    }
    ######################## Gallery Images : End ############################

    ######################## Thumbnail Image : Start ############################
    // validate and upload photo
    public function updatedThumbnailImage($thumbnail_image)
    {
        $this->validateOnly($thumbnail_image);

        // Crop and resize photo
        try {
            $this->thumbnail_image_name = singleImageUpload($thumbnail_image, 'product-', 'products');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteThumbnail()
    {
        $this->deletedImages[] = $this->thumbnail_image_name;

        $this->thumbnail_image = null;
        $this->thumbnail_image_name = null;
    }
    ######################## Thumbnail Image : Start ############################


    ######################## Real Time Validation : Start ############################
    public function updated($field)
    {
        $this->validateOnly($field);

        if ($field == 'base_price') {
            if ($this->base_price == null) {
                $this->base_price = 0;
            }
            $this->final_price = round($this->base_price - ($this->base_price * ($this->discount / 100)), 2);
        } elseif ($field == 'discount') {
            if ($this->discount == null) {
                $this->discount = 0;
            }
            $this->final_price = round($this->base_price - ($this->base_price * ($this->discount / 100)), 2);
        } elseif ($field == 'final_price') {
            if ($this->final_price == null) {
                $this->final_price = 0;
            }
            $this->discount = round((($this->base_price - $this->final_price) / $this->base_price) * 100, 2);
        }
    }
    ######################## Real Time Validation : End ############################


    ######################## Updated Supercategory : End ############################
    public function supercategoryUpdated($key)
    {
        if ($this->parentCategories[$key]['supercategory_id'] != 0) {
            $this->parentCategories[$key]['categories'] = Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $this->parentCategories[$key]['supercategory_id'])->get()->toArray();
            $this->parentCategories[$key]['category_id'] = 0;
            $this->parentCategories[$key]['subcategories'] = null;
            $this->parentCategories[$key]['subcategory_id'] = 0;
        } else {
            $this->parentCategories[$key]['categories'] = null;
            $this->parentCategories[$key]['category_id'] = 0;
            $this->parentCategories[$key]['subcategories'] = null;
            $this->parentCategories[$key]['subcategory_id'] = 0;
        }
    }
    ######################## Updated Supercategory : End ############################


    ######################## Updated Supercategory : End ############################
    public function categoryUpdated($key)
    {
        if ($this->parentCategories[$key]['category_id'] != 0) {
            $this->parentCategories[$key]['subcategories'] = Subcategory::select('id', 'name', 'category_id')->where('category_id', $this->parentCategories[$key]['category_id'])->get()->toArray();
            $this->parentCategories[$key]['subcategory_id'] = 0;
        } else {
            $this->parentCategories[$key]['subcategories'] = null;
            $this->parentCategories[$key]['subcategory_id'] = 0;
        }
    }
    ######################## Updated Supercategory : End ############################


    ######################## Delete Subcategory : End ############################
    public function deleteSubcategory($index)
    {
        unset($this->parentCategories[$index]);
    }
    ######################## Delete Subcategory : End ############################

    ######################## Add Subcategory : End ############################
    public function addSubcategory()
    {
        $this->parentCategories[] = [
            'supercategory_id' => 0,
            'supercategories' => Supercategory::select('id', 'name')->get()->toArray(),
            'category_id' => 0,
            'categories' => null,
            'subcategory_id' => 0,
            'subcategories' => null,
        ];
    }
    ######################## Add Subcategory : End ############################


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


    ######################## Save New Product : Start ############################
    public function save($new = false)
    {
        // dd(array_map(fn ($value) => $value['subcategory_id'], $this->parentCategories));
        $this->validate();

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => [
                    'ar' => $this->name['ar'],
                    'en' => $this->name['en'] ?? $this->name['ar']
                ],
                'slug' => $this->name['en'] ? strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->name['en']))) : '',
                'barcode' => $this->barcode,
                'weight' => $this->weight ?? 0,
                'quantity' => $this->quantity ?? 0,
                'low_stock' => $this->low_stock ?? 0,
                'base_price' => $this->base_price,
                'final_price' => $this->final_price,
                'points' => $this->points,
                'description' => [
                    'ar' => $this->description['ar'] ?? $this->description['en'],
                    'en' => $this->description['en'] ?? $this->description['ar']
                ],
                'model' => $this->model,
                'refundable' => $this->refundable ? 1 : 0,
                'video' => $this->video,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
                'free_shipping' => $this->free_shipping ? 1 : 0,
                'publish' => $this->publish ? 1 : 0,
                'under_reviewing' => $this->reviewing ? 1 : 0,
                'created_by' => auth()->user()->id,
                'brand_id' => $this->brand_id,
            ]);

            // Add Subcategories
            if (count($this->parentCategories)) {
                $subcategories_id = array_map(fn ($value) => $value['subcategory_id'], $this->parentCategories);
                $product->subcategories()->attach($subcategories_id);
            }

            if (count($this->gallery_images_name)) {
                foreach ($this->gallery_images_name as $key => $gallery_image_name) {
                    ProductImage::create([
                        'file_name' => $gallery_image_name,
                        'product_id' => $product->id,
                        'featured' => $key == $this->featured ? 1 : 0,
                    ]);
                }
            }

            if ($this->thumbnail_image_name != null) {
                ProductImage::create([
                    'file_name' => $this->thumbnail_image_name,
                    'product_id' => $product->id,
                    'is_thumbnail' => 1,
                ]);
            }

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/productsPages.Product added successfully'));
                redirect()->route('admin.products.create');
            } else {
                Session::flash('success', __('admin/productsPages.Product added successfully'));
                redirect()->route('admin.products.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Session::flash('error', __("admin/productsPages.Product hasn't been added"));
            redirect()->route('admin.products.index');
        }
    }
    ######################## Save New Product : End ############################

    ######################## Save Updated Product : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->product->update([
                'name' => [
                    'ar' => $this->name['ar'],
                    'en' => $this->name['en'] ?? $this->name['ar']
                ],
                'slug' => $this->name['en'] ? strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->name['en']))) : '',
                'barcode' => $this->barcode,
                'weight' => $this->weight ?? 0,
                'quantity' => $this->quantity ?? 0,
                'low_stock' => $this->low_stock ?? 0,
                'base_price' => $this->base_price,
                'final_price' => $this->final_price,
                'points' => $this->points,
                'description' => [
                    'ar' => $this->description['ar'] ?? $this->description['en'],
                    'en' => $this->description['en'] ?? $this->description['ar']
                ],
                'model' => $this->model,
                'refundable' => $this->refundable ? 1 : 0,
                'video' => $this->video,
                'meta_title' => $this->title,
                'meta_description' => $this->description_seo,
                'free_shipping' => $this->free_shipping ? 1 : 0,
                'publish' => $this->publish ? 1 : 0,
                'under_reviewing' => $this->reviewing ? 1 : 0,
                'created_by' => auth()->user()->id,
                'brand_id' => $this->brand_id,
            ]);

            // Add Subcategories
            $subcategories_id = array_map(fn ($value) => $value['subcategory_id'], $this->parentCategories);
            $this->product->subcategories()->sync($subcategories_id);

            $this->product->images()->delete();

            if (count($this->gallery_images_name)) {
                foreach ($this->gallery_images_name as $key => $gallery_image_name) {
                    ProductImage::create([
                        'file_name' => $gallery_image_name,
                        'product_id' => $this->product->id,
                        'featured' => $key == $this->featured ? 1 : 0,
                    ]);
                }
            }

            if ($this->thumbnail_image_name != null) {
                ProductImage::create([
                    'file_name' => $this->thumbnail_image_name,
                    'product_id' => $this->product->id,
                    'is_thumbnail' => 1,
                ]);
            }


            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'products');
            }

            DB::commit();

            Session::flash('success', __('admin/productsPages.Product updated successfully'));
            redirect()->route('admin.products.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/productsPages.Product hasn't been updated"));
            redirect()->route('admin.products.index');
        }
    }
    ######################## Save Updated Product : End ############################

}
