<?php

namespace App\Http\Livewire\Admin\Collections;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;


class CollectionForm extends Component
{
    use WithFileUploads;

    public $collection_id;
    public $collection;
    public $old_collection_id;
    public $gallery_images = [], $gallery_images_name = [], $featured = 0, $deletedImages = [];
    public $gallery_images_featured;
    public $thumbnail_image,  $thumbnail_image_name, $video;
    public $specs = [];
    public $name = ["ar" => null, "en" => null], $model, $barcode, $weight, $description = ['ar' => null, 'en' => null], $publish = true, $refundable = true;
    public $profit_margin = 0, $original_price = 0, $base_price = 0, $discount, $final_price, $points, $free_shipping = false, $reviewing = false;
    public $seo_keywords;

    public $search, $productsList, $products = [];

    // Complementary Products
    public $complementaryHighestRank = 1;
    public $complementaryItems = [];

    // Related Products
    public $relatedHighestRank = 1;
    public $relatedItems = [];

    const PRODUCT = 'Product';
    const COLLECTION = 'Collection';
    const LIMIT = 12;

    protected $listeners = [
        "descriptionAr",
        "descriptionEn",
        "clearSearch",
        "addSelected"
    ];

    public function rules()
    {
        return [
            'gallery_images.*'      =>          'nullable|mimes:jpg,jpeg,png|max:2048',
            'thumbnail_image'       =>          'nullable|mimes:jpg,jpeg,png|max:2048',
            'video'                 =>          'nullable|string',

            'name.ar'               =>          'required|string|max:100|min:3',
            'name.en'               =>          'nullable|string|max:100|min:3',

            'model'                 =>          'nullable|string|max:100',
            'barcode'               =>          'nullable|string|max:200',
            'weight'                =>          'nullable|numeric|min:0|max:999999',
            'publish'               =>          'boolean',
            'refundable'            =>          'boolean',

            'original_price'        =>          'required|numeric|min:0|max:999999',
            'base_price'            =>          'required|numeric|min:0|max:999999',
            'discount'              =>          'nullable|numeric|min:0|max:100',
            'final_price'           =>          'nullable|numeric|min:0|max:999999|lte:base_price',
            'points'                =>          'nullable|integer|min:0|max:999999',
            'free_shipping'         =>          'boolean',
            'reviewing'             =>          'boolean',

            'products'              =>          'array|min:1',
            'products.*.id'         =>          'required|exists:products,id',
            'complementaryItems.*.pivot.rank' => 'required|integer|min:0|max:127',
            'relatedItems.*.pivot.rank'       => 'required|integer|min:0|max:127'
        ];
    }

    public function messages()
    {
        return [
            'products.min' => __('admin/productsPages.The collection must contain at least one product'),
            'products.*.id' => __('admin/productsPages.This product does not exist in the database'),
        ];
    }

    ######################## Mount :: Start ############################
    public function mount()
    {
        $this->productsList = collect([]);

        // If editing collection
        if ($this->collection_id) {

            // Get Old Collection's data
            $collection = Collection::with([
                'products' => fn ($q) => $q->with('thumbnail')->select([
                    'products.id',
                    'name',
                    'original_price',
                    'base_price',
                    'final_price',
                    'free_shipping',
                    'brand_id',
                    'points',
                    'slug',
                    'under_reviewing',
                    'products.quantity',
                ]),
                'images',
                'specs',
                'relatedProducts' => fn ($q) => $q->select([
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.brand_id',
                ])->with(
                    ['brand' => fn ($q) => $q->select('id', 'name'), 'thumbnail']
                ),
                'relatedCollections' => fn ($q) => $q->select([
                    'collections.id',
                    'collections.name',
                    'collections.slug',
                ])->with(
                    ['thumbnail']
                ),
                'complementedProducts' => fn ($q) => $q->select([
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.brand_id',
                ])->with(
                    ['brand' => fn ($q) => $q->select('id', 'name'), 'thumbnail']
                ),
                'complementedCollections' => fn ($q) => $q->select([
                    'collections.id',
                    'collections.name',
                    'collections.slug',
                ])->with(
                    ['thumbnail']
                ),
            ])->findOrFail($this->collection_id);

            $this->collection = $collection;

            // Old Media
            $collections_images = $collection->images;

            $this->gallery_images_name = $collections_images
                ->where('is_thumbnail', 0)
                ->pluck('file_name')
                ->toArray();

            $this->gallery_images_featured = $collections_images
                ->where('is_thumbnail', 0)
                ->pluck('featured')
                ->toArray();

            foreach ($this->gallery_images_featured as $key => $value) {
                if ($value == 1) {
                    $this->featured = $key;
                }
            }

            $this->thumbnail_image_name = $collections_images
                ->where('is_thumbnail', 1)
                ->first() ? $collections_images->where('is_thumbnail', 1)->first()->file_name : null;

            // Old Collection's Products
            $products = $collection->products->map(function ($product) {
                $productArray = $product->toArray();
                $productArray['amount'] = $product->pivot->quantity;
                $productArray['avg_rating'] = $product->reviews->avg('rating');
                return $productArray;
            })->toArray();

            $products_ids = array_map(fn ($product) => $product['id'], $products);

            $this->products = array_combine($products_ids, $products);

            // Old Collection's Info
            $this->name = [
                'ar' => $collection->getTranslation('name', 'ar'),
                'en' => $collection->getTranslation('name', 'en')
            ];

            $this->video = $collection->video;
            $this->model = $collection->model;
            $this->barcode = $collection->barcode;
            $this->weight = $collection->weight;
            $this->description = [
                'ar' => $collection->getTranslation('description', 'ar'),
                'en' => $collection->getTranslation('description', 'en'),
            ];

            $this->publish = $collection->publish;
            $this->refundable = $collection->refundable;

            if ($collection->specs != null) {
                foreach ($collection->specs as $spec) {
                    $this->specs[] = [
                        'ar' => [
                            'title' => $spec->getTranslation('title', 'ar'),
                            'value' => $spec->getTranslation('value', 'ar'),
                        ],
                        'en' => [
                            'title' => $spec->getTranslation('title', 'en'),
                            'value' => $spec->getTranslation('value', 'en'),
                        ]
                    ];
                }
            }

            // Old Stock and Price
            $this->original_price = $collection->original_price;
            $this->profit_margin = round($collection->final_price - $collection->original_price);
            $this->base_price = $collection->base_price;
            $this->final_price = $collection->final_price;
            $this->discount = $this->base_price > 0 ? round((($this->base_price - $this->final_price) / $this->base_price) * 100, 2) : 0;
            $this->points = $collection->points;
            $this->free_shipping = $collection->free_shipping;
            $this->reviewing = $collection->under_reviewing;

            // SEO
            $this->seo_keywords = $collection->meta_keywords;

            // Get Old Complementary Products
            $complementaryProduct = $collection->complementedProducts->toArray();
            $complementaryCollection = $collection->complementedCollections->toArray();
            $this->complementaryItems = array_merge($this->complementaryItems, $complementaryProduct, $complementaryCollection);
            $this->complementaryHighestRank = count($this->complementaryItems) ? max(array_map(fn ($product) => $product['pivot']['rank'], $this->complementaryItems)) + 1 : 1;

            // Get Old Related Products
            $relatedProduct = $collection->relatedProducts->toArray();
            $relatedCollection = $collection->relatedCollections->toArray();
            $this->relatedItems = array_merge($this->relatedItems, $relatedProduct, $relatedCollection);
            $this->relatedHighestRank = count($this->relatedItems) ? max(array_map(fn ($product) => $product['pivot']['rank'], $this->relatedItems)) + 1 : 1;
        } elseif ($this->old_collection_id) {
            // Get Old Collection's data
            $collection = Collection::with([
                'products' => fn ($q) => $q->with('thumbnail')->select([
                    'products.id',
                    'name',
                    'original_price',
                    'base_price',
                    'final_price',
                    'free_shipping',
                    'brand_id',
                    'points',
                    'slug',
                    'under_reviewing',
                    'products.quantity',
                ]),
                'images',
                'relatedProducts' => fn ($q) => $q->select([
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.brand_id',
                ])->with(
                    ['brand' => fn ($q) => $q->select('id', 'name'), 'thumbnail']
                ),
                'relatedCollections' => fn ($q) => $q->select([
                    'collections.id',
                    'collections.name',
                    'collections.slug',
                ])->with(
                    ['thumbnail']
                ),
                'complementedProducts' => fn ($q) => $q->select([
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.brand_id',
                ])->with(
                    ['brand' => fn ($q) => $q->select('id', 'name'), 'thumbnail']
                ),
                'complementedCollections' => fn ($q) => $q->select([
                    'collections.id',
                    'collections.name',
                    'collections.slug',
                ])->with(
                    ['thumbnail']
                )
            ])->findOrFail($this->old_collection_id);

            $this->collection = $collection;

            // Old Data
            $this->name = [
                'ar' => "",
                'en' => ""
            ];

            $this->weight = $collection->weight;

            $this->description = [
                'ar' => $collection->getTranslation('description', 'ar'),
                'en' => $collection->getTranslation('description', 'en'),
            ];

            $this->publish = $collection->publish;

            $products = $collection->products->map(function ($product) {
                $productArray = $product->toArray();
                $productArray['amount'] = $product->pivot->quantity;
                $productArray['avg_rating'] = $product->reviews->avg('rating');
                return $productArray;
            })->toArray();

            $products_ids = array_map(fn ($product) => $product['id'], $products);

            $this->products = array_combine($products_ids, $products);

            if ($collection->specs != null) {
                foreach ($collection->specs as $spec) {
                    $this->specs[] = [
                        'ar' => [
                            'title' => $spec->getTranslation('title', 'ar'),
                            'value' => $spec->getTranslation('value', 'ar'),
                        ],
                        'en' => [
                            'title' => $spec->getTranslation('title', 'en'),
                            'value' => $spec->getTranslation('value', 'en'),
                        ]
                    ];
                }
            }

            // SEO
            $this->seo_keywords = $collection->meta_keywords;

            // Get Old Complementary Products
            $complementaryProduct = $collection->complementedProducts->toArray();
            $complementaryCollection = $collection->complementedCollections->toArray();
            $this->complementaryItems = array_merge($this->complementaryItems, $complementaryProduct, $complementaryCollection);
            $this->complementaryHighestRank = count($this->complementaryItems) ? max(array_map(fn ($product) => $product['pivot']['rank'], $this->complementaryItems)) + 1 : 1;

            // Get Old Related Products
            $relatedProduct = $collection->relatedProducts->toArray();
            $relatedCollection = $collection->relatedCollections->toArray();
            $this->relatedItems = array_merge($this->relatedItems, $relatedProduct, $relatedCollection);
            $this->relatedHighestRank = count($this->relatedItems) ? max(array_map(fn ($product) => $product['pivot']['rank'], $this->relatedItems)) + 1 : 1;

            $this->priceUpdate();
        }
    }
    ######################## Mount :: End ############################


    ######################## Render :: Start ############################
    public function render()
    {
        return view('livewire.admin.collections.collection-form');
    }
    ######################## Render :: End ############################

    ######################## Search Products :: Start ############################
    public function updatedSearch()
    {
        if ($this->search != "") {
            $this->productsList = Product::select([
                'id',
                'name',
                'original_price',
                'base_price',
                'final_price',
                'free_shipping',
                'brand_id',
                'points',
                'quantity',
            ])
                ->with(['brand' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->whereNotIn('id', array_map(fn ($product) => $product['id'], $this->products))
                ->where(function ($q) {
                    $q->where('name->ar', 'like', '%' . $this->search . '%')
                        ->orWhere('name->en', 'like', '%' . $this->search . '%')
                        ->orWhere('original_price', 'like', '%' . $this->search . '%')
                        ->orWhere('base_price', 'like', '%' . $this->search . '%')
                        ->orWhere('final_price', 'like', '%' . $this->search . '%')
                        ->orWhere('points', 'like', '%' . $this->search . '%');
                })
                ->get();
        } else {
            $this->productsList = collect([]);
        }
    }
    ######################## Search Products :: End ############################

    ######################## Clear Search :: End ############################
    public function clearSearch()
    {
        $this->search = null;
    }
    ######################## Clear Search :: End ############################

    ######################## Add product to the Collection :: End ############################
    public function addProduct($product_id)
    {
        $product = Product::with('thumbnail')
            ->select([
                'id',
                'name',
                'original_price',
                'base_price',
                'final_price',
                'free_shipping',
                'brand_id',
                'points',
                'slug',
                'under_reviewing',
                'quantity',
            ])
            ->findOrFail($product_id)
            ->append('avg_rating')
            ->toArray();

        $product['amount'] = 1;

        $this->products[$product_id] = $product;

        $this->priceUpdate();
        $this->clearSearch();
    }
    ######################## Add product to the Collection :: End ############################

    ######################## Update the product amount of the Collection :: End ############################
    public function amountUpdated($product_id, $amount)
    {
        if ($amount > 0) {
            $this->products[$product_id]['amount'] =  $amount;
        } else {
            unset($this->products[$product_id]);
        }

        $this->priceUpdate();
    }
    ######################## Update the product amount of the Collection :: End ############################

    ######################## Remove All products From the Collection :: End ############################
    public function clearProducts()
    {
        $this->products = [];

        $this->priceUpdate();
    }
    ######################## Remove All products From the Collection :: End ############################


    ######################## Update Original Price and Base Price :: Start ############################
    public function priceUpdate()
    {
        $this->original_price = round(array_sum(array_map(function ($product) {
            return $product['amount'] * $product['original_price'];
        }, $this->products)), 2);

        $this->base_price = round(array_sum(array_map(function ($product) {
            return $product['amount'] * $product['base_price'];
        }, $this->products)), 2);

        $this->final_price = round(array_sum(array_map(function ($product) {
            return $product['amount'] * $product['final_price'];
        }, $this->products)), 2);

        $this->discount = $this->base_price ? round((($this->base_price - $this->final_price) * 100) / $this->base_price, 2) : 0;

        $this->profitMarginUpdate();
    }
    ######################## Update Original Price and Base Price :: Start ############################

    ######################## Update Profit Margin :: Start ############################
    public function profitMarginUpdate()
    {
        $this->profit_margin = round($this->final_price - $this->original_price, 2);
    }
    ######################## Update Profit Margin :: Start ############################

    ######################## Publish Toggle :: Start ############################
    public function publish()
    {
        $this->publish = !$this->publish;
    }
    ######################## Publish Toggle :: End ############################


    ######################## Refundable Toggle :: Start ############################
    public function refund()
    {
        $this->refundable = !$this->refundable;
    }
    ######################## Refundable Toggle :: End ############################


    ######################## Free Shipping Toggle :: Start ############################
    public function free_shipping()
    {
        $this->free_shipping = !$this->free_shipping;
    }
    ######################## Free Shipping Toggle :: End ############################


    ######################## Reviewing Toggle :: Start ############################
    public function reviewing()
    {
        $this->reviewing = !$this->reviewing;
    }
    ######################## Reviewing Toggle :: End ############################


    ######################## Gallery Images :: Start ############################
    // validate and upload photo
    public function updatedGalleryImages($gallery_images)
    {
        $this->validate([
            'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        foreach ($gallery_images as $key => $gallery_image) {
            $file_name = str_replace(" ", "-", pathinfo($gallery_image->getClientOriginalName(), PATHINFO_FILENAME));
            try {
                $this->gallery_images_name[] = singleImageUpload($gallery_image, $file_name . '-', 'collections');
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
    ######################## Gallery Images :: End ############################

    ######################## Thumbnail Image :: Start ############################
    // validate and upload photo
    public function updatedThumbnailImage($thumbnail_image)
    {
        $this->validateOnly($thumbnail_image);

        $file_name = str_replace(" ", "-", pathinfo($thumbnail_image->getClientOriginalName(), PATHINFO_FILENAME));
        // Crop and resize photo
        try {
            $this->thumbnail_image_name = singleImageUpload($thumbnail_image, $file_name . '-', 'collections');
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
    ######################## Thumbnail Image :: Start ############################


    ######################## Real Time Validation :: Start ############################
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
            $this->discount = $this->base_price > 0 ? round((($this->base_price - $this->final_price) / $this->base_price) * 100, 2) : 0;
        }

        if (in_array($field, ['original_price', 'base_price', 'discount', 'final_price'])) {
            $this->profitMarginUpdate();
        }
    }
    ######################## Real Time Validation :: End ############################


    ######################## Updated Arabic description :: Start ############################
    public function descriptionAr($value)
    {
        $this->description['ar'] = $value;
    }
    ######################## Updated Arabic description :: End ############################


    ######################## Updated English description :: Start ############################
    public function descriptionEn($value)
    {
        $this->description['en'] = $value;
    }
    ######################## Updated English description :: End ############################


    ######################## Delete Specification :: Start ############################
    public function deleteSpec($index)
    {
        unset($this->specs[$index]);
    }
    ######################## Delete Specification :: End ############################

    ######################## Add Specification :: Start ############################
    public function addSpec()
    {
        $this->specs[] = [
            'ar' => [
                'title' => null,
                'value' => null,
            ],
            'en' => [
                'title' => null,
                'value' => null,
            ]
        ];
    }
    ######################## Add Specification :: End ############################

    ######################## Update Complementary & Related Products :: Start ############################
    public function addSelected($model, $selectedCollections, $selectedProducts)
    {
        if ($model == "complementary-items-list") {
            $this->addComplementaryProducts($selectedProducts);
            $this->addComplementaryCollections($selectedCollections);
        } elseif ($model == "related-items-list") {
            $this->addRelatedProducts($selectedProducts);
            $this->addRelatedCollections($selectedCollections);
        }
    }
    ######################## Update Complementary & Related Products :: End ############################

    ######################## Add Complementary Products :: Start ############################
    public function addComplementaryProducts($selectedProducts)
    {
        $complementaryProductsIds = array_map(fn ($product) => $product['type'] == self::PRODUCT ? $product['id'] : null, $this->complementaryItems);

        foreach ($selectedProducts as $selectedProduct) {
            if (!in_array($selectedProduct, $complementaryProductsIds)) {
                $this->addComplementaryProduct($selectedProduct, self::PRODUCT);
            }
        }
    }
    ######################## Add Complementary Products :: End ############################

    ######################## Add Complementary Collections :: Start ############################
    public function addComplementaryCollections($selectedCollections)
    {
        $complementaryCollectionsIds = array_map(fn ($product) => $product['type'] == self::COLLECTION ? $product['id'] : null, $this->complementaryItems);

        foreach ($selectedCollections as $selectedCollection) {
            if (!in_array($selectedCollection, $complementaryCollectionsIds)) {
                $this->addComplementaryProduct($selectedCollection, self::COLLECTION);
            }
        }
    }
    ######################## Add Complementary Collections :: End ############################

    ######################## Add Related Products :: Start ############################
    public function addRelatedProducts($selectedProducts)
    {
        $relatedProductsIds = array_map(fn ($product) => $product['type'] == self::PRODUCT ? $product['id'] : null, $this->relatedItems);

        foreach ($selectedProducts as $selectedProduct) {
            if (!in_array($selectedProduct, $relatedProductsIds)) {
                $this->addRelatedProduct($selectedProduct, self::PRODUCT);
            }
        }
    }
    ######################## Add Related Products :: End ############################

    ######################## Add Related Collections :: Start ############################
    public function addRelatedCollections($selectedCollections)
    {
        $relatedCollectionsIds = array_map(fn ($product) => $product['type'] == self::COLLECTION ? $product['id'] : null, $this->relatedItems);

        foreach ($selectedCollections as $selectedCollection) {
            if (!in_array($selectedCollection, $relatedCollectionsIds)) {
                $this->addRelatedProduct($selectedCollection, self::COLLECTION);
            }
        }
    }
    ######################## Add Related Collections :: End ############################

    ######################## Add Complementary Product :: Start ############################
    public function addComplementaryProduct(int $complementaryProductId, string $complementaryProductType)
    {
        $complementaryProduct = $this->fetchProductOrCollection($complementaryProductId, $complementaryProductType);
        $complementaryProduct['pivot']['rank'] = 0;

        if ($complementaryProduct) {
            $this->complementaryItems[] = $complementaryProduct;
        }
    }
    ######################## Add Complementary Product :: End ############################

    ######################## Delete Complementary Product :: Start ############################
    public function deleteComplementaryProduct($complementaryProductId, $complementaryProductType)
    {
        $this->complementaryItems = array_filter(
            $this->complementaryItems,
            fn ($product) => $product['id'] != $complementaryProductId || $product['type'] != $complementaryProductType
        );
    }
    ######################## Delete Complementary Product :: End ############################

    ######################## Clear Complementary Products :: Start ############################
    public function clearComplementaryProducts()
    {
        $this->complementaryItems = [];
    }
    ######################## Clear Complementary Products :: End ############################

    ######################## Add Related Product :: Start ############################
    public function addRelatedProduct($relatedProductId, $relatedProductType)
    {
        $relatedProduct = $this->fetchProductOrCollection($relatedProductId, $relatedProductType);
        $relatedProduct['pivot']['rank'] = 0;

        if ($relatedProduct) {
            $this->relatedItems[] = $relatedProduct;
        }
    }
    ######################## Add Related Product :: End ############################

    ######################## Delete Related Product :: Start ############################
    public function deleteRelatedProduct($relatedProductId, $relatedProductType)
    {
        $this->relatedItems = array_filter(
            $this->relatedItems,
            fn ($product) => $product['id'] != $relatedProductId || $product['type'] != $relatedProductType
        );
    }
    ######################## Delete Related Product :: End ############################

    ######################## Clear Related Products :: Start ############################
    public function clearRelatedProducts()
    {
        $this->relatedItems = [];
    }
    ######################## Clear Related Products :: End ############################

    ######################## Update Complementary Product Rank :: Start ############################
    public function editComplementaryRank($key)
    {
        $this->complementaryItems[$key]['pivot']['rank'] = $this->complementaryHighestRank++;
    }
    ######################## Update Complementary Product Rank :: End ############################

    ######################## Update Related Product Rank :: Start ############################
    public function editRelatedRank($key)
    {
        $this->relatedItems[$key]['pivot']['rank'] = $this->relatedHighestRank++;
    }
    ######################## Update Related Product Rank :: End ############################

    ######################## Reset Complementary Products Rank :: Start ############################
    public function resetComplementaryRank()
    {
        $this->complementaryHighestRank = 1;

        $this->complementaryItems = array_map(
            function ($product) {
                $product['pivot']['rank'] = 0;
                return $product;
            },
            $this->complementaryItems
        );
    }
    ######################## Reset Complementary Products Rank :: End ############################

    ######################## Reset Related Products Rank :: Start ############################
    public function resetRelatedRank()
    {
        $this->relatedHighestRank = 1;

        $this->relatedItems = array_map(
            function ($product) {
                $product['pivot']['rank'] = 0;
                return $product;
            },
            $this->relatedItems
        );
    }
    ######################## Reset Related Products Rank :: End ############################

    ######################## Clean Complementary Ranking :: Start ############################
    public function cleanComplementaryRanking()
    {
        $max = 1;

        // Sort Complementary Products by Rank
        usort($this->complementaryItems, function ($a, $b) {
            return $a['pivot']['rank'] <=> $b['pivot']['rank'];
        });

        // Update Complementary Products Rank
        foreach ($this->complementaryItems as $key => $complementaryItem) {
            if ($this->complementaryItems[$key]['pivot']['rank'] != 0) {
                $this->complementaryItems[$key]['pivot']['rank'] = $max++;
            }
        }

        $this->complementaryHighestRank = $max;
    }
    ######################## Clean Complementary Ranking :: End ############################

    ######################## Clean Related Ranking :: Start ############################
    public function cleanRelatedRanking()
    {
        $max = 1;

        // Sort Related Products by Rank
        usort($this->relatedItems, function ($a, $b) {
            return $a['pivot']['rank'] <=> $b['pivot']['rank'];
        });

        // Update Related Products Rank
        foreach ($this->relatedItems as $key => $relatedItem) {
            if ($this->relatedItems[$key]['pivot']['rank'] != 0) {
                $this->relatedItems[$key]['pivot']['rank'] = $max++;
            }
        }

        $this->relatedHighestRank = $max;
    }
    ######################## Clean Related Ranking :: End ############################

    ######################## Fetch Product Or Collection :: Start ############################
    private function fetchProductOrCollection(int $productId, string $productType)
    {
        if ($productType == self::PRODUCT) {
            return Product::select([
                'id',
                'name',
                'slug',
                'brand_id',
            ])
                ->with([
                    'brand' => function ($q) {
                        $q->select('id', 'name');
                    }, 'thumbnail'
                ])
                ->findOrFail($productId)
                ->toArray();
        } elseif ($productType == self::COLLECTION) {
            return Collection::select([
                'id',
                'name',
                'slug',
                DB::raw('NULL as brand_id'),
            ])
                ->with([
                    'thumbnail'
                ])
                ->findOrFail($productId)
                ->toArray();
        }
    }
    ######################## Fetch Product Or Collection :: End ############################

    ######################## Save New Product :: Start ############################
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $collection = Collection::create([
                'name' => [
                    'ar' => $this->name['ar'],
                    'en' => $this->name['en'] ?? $this->name['ar']
                ],
                'slug' => [
                    'ar' => Str::slug($this->name['ar'], '-', Null),
                    'en' => Str::slug($this->name['en'], '-'),
                ],
                'barcode' => $this->barcode,
                'video' => $this->video,
                'weight' => $this->weight ?? 0,
                'original_price' => $this->original_price,
                'base_price' => $this->base_price,
                'final_price' => $this->final_price,
                'points' => $this->points,
                'description' => [
                    'ar' => $this->description['ar'] ?? $this->description['en'],
                    'en' => $this->description['en'] ?? $this->description['ar']
                ],
                'model' => $this->model,
                'refundable' => $this->refundable ? 1 : 0,
                'meta_keywords' => $this->seo_keywords,
                'free_shipping' => $this->free_shipping ? 1 : 0,
                'publish' => $this->publish ? 1 : 0,
                'under_reviewing' => $this->reviewing ? 1 : 0,
                'created_by' => auth()->user()->id,
            ]);

            if (count($this->products)) {
                $collection->products()->sync(
                    array_map(fn ($product) => ['quantity' => $product['amount']], $this->products)
                );
            }

            if (count($this->gallery_images_name)) {
                foreach ($this->gallery_images_name as $key => $gallery_image_name) {
                    $collection->images()->create([
                        'file_name' => $gallery_image_name,
                        'featured' => $key == $this->featured ? 1 : 0,
                    ]);
                }
            }

            if ($this->thumbnail_image_name != null) {
                $collection->images()->create([
                    'file_name' => $this->thumbnail_image_name,
                    'is_thumbnail' => 1,
                ]);
            }

            // Add Specs
            if (count($this->specs)) {
                foreach ($this->specs as $spec) {
                    $collection->specs()->create([
                        'title' => [
                            'ar' => $spec['ar']['title'],
                            'en' => $spec['en']['title']
                        ],
                        'value' => [
                            'ar' => $spec['ar']['value'],
                            'en' => $spec['en']['value']
                        ],
                    ]);
                }
            }

            // Add Complementary Products
            if (count($this->complementaryItems)) {
                $complementaryProducts = [];
                $complementaryCollections = [];

                foreach ($this->complementaryItems as $key => $complementaryItem) {
                    if ($complementaryItem['type'] == self::PRODUCT) {
                        $complementaryProducts[$complementaryItem['id']] = ['rank' => $complementaryItem['pivot']['rank']];
                    } elseif ($complementaryItem['type'] == self::COLLECTION) {
                        $complementaryCollections[$complementaryItem['id']] = ['rank' => $complementaryItem['pivot']['rank']];
                    }
                }

                $collection->complementedProducts()->sync($complementaryProducts);
                $collection->complementedCollections()->sync($complementaryCollections);
            }

            // Add Related Products
            if (count($this->relatedItems)) {
                $relatedProducts = [];
                $relatedCollections = [];

                foreach ($this->relatedItems as $key => $relatedItem) {
                    if ($relatedItem['type'] == self::PRODUCT) {
                        $relatedProducts[$relatedItem['id']] = ['rank' => $relatedItem['pivot']['rank']];
                    } elseif ($relatedItem['type'] == self::COLLECTION) {
                        $relatedCollections[$relatedItem['id']] = ['rank' => $relatedItem['pivot']['rank']];
                    }
                }

                $collection->relatedProducts()->sync($relatedProducts);
                $collection->relatedCollections()->sync($relatedCollections);
            }

            DB::commit();

            // Remove Old Images
            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'collections');
            }

            if ($new) {
                Session::flash('success', __('admin/productsPages.Collection added successfully'));
                redirect()->route('admin.collections.create');
            } else {
                Session::flash('success', __('admin/productsPages.Collection added successfully'));
                redirect()->route('admin.collections.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Session::flash('error', __("admin/productsPages.Collection hasn't been added"));
            redirect()->route('admin.collections.index');
        }
    }
    ######################## Save New Collection :: End ############################

    ######################## Save Updated Collection :: Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->collection->update([
                'name' => [
                    'ar' => $this->name['ar'],
                    'en' => $this->name['en'] ?? $this->name['ar']
                ],
                'slug' => [
                    'ar' => Str::slug($this->name['ar'], '-', Null),
                    'en' => Str::slug($this->name['en'], '-'),
                ],
                'video' => $this->video,
                'barcode' => $this->barcode,
                'weight' => $this->weight ?? 0,
                'original_price' => $this->original_price,
                'base_price' => $this->base_price,
                'final_price' => $this->final_price,
                'points' => $this->points,
                'description' => [
                    'ar' => $this->description['ar'] ?? $this->description['en'],
                    'en' => $this->description['en'] ?? $this->description['ar']
                ],
                'specs' => json_encode(array_values($this->specs)),
                'model' => $this->model,
                'refundable' => $this->refundable ? 1 : 0,
                'meta_keywords' => $this->seo_keywords,
                'free_shipping' => $this->free_shipping ? 1 : 0,
                'publish' => $this->publish ? 1 : 0,
                'under_reviewing' => $this->reviewing ? 1 : 0,
                'created_by' => auth()->user()->id,
            ]);

            $this->collection->images()->delete();

            if (count($this->products)) {
                $this->collection->products()->sync(
                    array_map(fn ($q) => ['quantity' => $q['amount']], $this->products)
                );
            }

            if (count($this->gallery_images_name)) {
                foreach ($this->gallery_images_name as $key => $gallery_image_name) {
                    $this->collection->images()->create([
                        'file_name' => $gallery_image_name,
                        'featured' => $key == $this->featured ? 1 : 0,
                    ]);
                }
            }

            if ($this->thumbnail_image_name != null) {
                $this->collection->images()->create([
                    'file_name' => $this->thumbnail_image_name,
                    'is_thumbnail' => 1,
                ]);
            }

            // Edit Specs
            $this->collection->specs()->delete();

            if (count($this->specs)) {
                foreach ($this->specs as $spec) {
                    $this->collection->specs()->create([
                        'title' => [
                            'ar' => $spec['ar']['title'],
                            'en' => $spec['en']['title']
                        ],
                        'value' => [
                            'ar' => $spec['ar']['value'],
                            'en' => $spec['en']['value']
                        ],
                    ]);
                }
            }

            // Delete Old Images
            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'collections');
            }

            // Add Complementary Products
            if (count($this->complementaryItems)) {
                $complementaryProducts = [];
                $complementaryCollections = [];

                foreach ($this->complementaryItems as $key => $complementaryItem) {
                    if ($complementaryItem['type'] == self::PRODUCT) {
                        $complementaryProducts[$complementaryItem['id']] = ['rank' => $complementaryItem['pivot']['rank']];
                    } elseif ($complementaryItem['type'] == self::COLLECTION) {
                        $complementaryCollections[$complementaryItem['id']] = ['rank' => $complementaryItem['pivot']['rank']];
                    }
                }

                $this->collection->complementedProducts()->sync($complementaryProducts);
                $this->collection->complementedCollections()->sync($complementaryCollections);
            }

            // Add Related Products
            if (count($this->relatedItems)) {
                $relatedProducts = [];
                $relatedCollections = [];

                foreach ($this->relatedItems as $key => $relatedItem) {
                    if ($relatedItem['type'] == self::PRODUCT) {
                        $relatedProducts[$relatedItem['id']] = ['rank' => $relatedItem['pivot']['rank']];
                    } elseif ($relatedItem['type'] == self::COLLECTION) {
                        $relatedCollections[$relatedItem['id']] = ['rank' => $relatedItem['pivot']['rank']];
                    }
                }

                $this->collection->relatedProducts()->sync($relatedProducts);
                $this->collection->relatedCollections()->sync($relatedCollections);
            }

            DB::commit();

            Session::flash('success', __('admin/productsPages.Collection updated successfully'));
            redirect()->route('admin.collections.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            // throw $th;
            Session::flash('error', __("admin/productsPages.Collection hasn't been updated"));
            redirect()->route('admin.collections.index');
        }
    }
    ######################## Save Updated Collection :: End ############################

}
