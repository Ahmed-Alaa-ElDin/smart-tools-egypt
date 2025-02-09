<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Znck\Eloquent\Traits\BelongsToThrough;

class Product extends Model
{
    use HasRelationships;
    use BelongsToThrough;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    protected $connection = "mysql";

    public $translatable = ['name', 'description', 'slug'];

    protected $fillable = [
        'name',
        'slug',
        'barcode',
        'weight',
        'quantity',
        'low_stock',
        "original_price",
        'base_price',
        'final_price',
        'points',
        'description',
        'model',
        'refundable',
        'video',
        'meta_keywords',
        'free_shipping',
        'publish',
        'under_reviewing',
        'created_by',
        'brand_id',
    ];

    protected $appends = [
        "avg_rating",
        // "can_review",
        'type'
    ];

    protected $with = [
        'reviews',
        'orders',
        'brand',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    // One to many relationship (Inverse)  Brand --> Products
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // One to many relationship (Inverse)  User --> Products
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Many to many relationship  Subcategories --> Products
    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class);
    }

    /**
     * Get unique categories from the product's subcategories.
     */
    public function getCategoriesAttribute()
    {
        // Load the category for each subcategory, then unique them.
        return $this->subcategories->map(function ($subcategory) {
            return $subcategory->category;
        })->filter() // Remove nulls if any
            ->unique('id')
            ->values();
    }

    /**
     * Get unique supercategories from the product's subcategories.
     */
    public function getSupercategoriesAttribute()
    {
        return $this->subcategories->map(function ($subcategory) {
            // Make sure both category and supercategory exist.
            return $subcategory->category ? $subcategory->category->supercategory : null;
        })->filter() // Remove nulls
            ->unique('id')
            ->values();
    }
    // One to many relationship Product --> Image
    public function images()
    {
        return $this->morphMany(Image::class, "imagable");
    }

    // One to one relationship Product --> Thumbnail
    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imagable')->where('is_thumbnail', 1);
    }

    // many to many relationship (polymorphic)  Product --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }

    // many to many relationship (polymorphic)  Product --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'offerable')->withPivot([
            'value',
            'type',
        ]);
    }

    // one to many relationship Product --> Specs
    public function specs()
    {
        return $this->hasMany(ProductSpec::class);
    }

    // many to many relationship Product --> Related Products
    public function relatedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'product_product_related',
            'first_product_id',
            'second_product_id'
        )->withPivot([
            'rank',
        ]);
    }

    // many to many relationship Product --> Related Collections
    public function relatedCollections()
    {
        return $this->belongsToMany(
            Collection::class,
            'collection_product_related',
            'product_id',
            'collection_id'
        )->withPivot([
            'rank',
        ]);
    }

    // many to many relationship Product --> Complemented Products
    public function complementedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'product_product_complemented',
            'first_product_id',
            'second_product_id'
        )->withPivot([
            'rank',
        ]);
    }

    // many to many relationship Product --> Complemented Collections
    public function complementedCollections()
    {
        return $this->belongsToMany(
            Collection::class,
            'collection_product_complemented',
            'product_id',
            'collection_id'
        )->withPivot([
            'rank',
        ]);
    }

    public function validOffers()
    {
        return $this->morphToMany(Offer::class, 'offerable')
            ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
            ->withPivot([
                'value',
                'type',
            ]);
    }

    // Many to many relationship  Sections --> Products
    public function sections()
    {
        return $this->morphToMany(Section::class, 'sectionable')->withPivot(['rank']);
    }

    // One to many relationship Product --> Reviews
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // many to many relationship Product --> Orders
    public function orders()
    {
        return $this->morphToMany(Order::class, 'orderable')->withPivot(
            'order_id',
            'quantity',
            'original_price',
            'price',
            'points',
            'coupon_discount',
            'coupon_points'
        )->withTimestamps();
    }

    // many to many relationship Product --> Collections
    public function collections()
    {
        return $this->belongsToMany(Collection::class)->withPivot(
            'quantity',
        )->withTimestamps();
    }

    ############# Appends :: Start #############
    public function getAvgRatingAttribute()
    {
        return $this->reviews->avg('rating') ?? 0;
    }

    public function getCanReviewAttribute()
    {
        return $this->reviews->where('user_id', auth()->id())->count() == 0 && $this->orders->where('user_id', auth()->id())->count() > 0 ? true : false;
    }

    // public function getBestOfferAttribute()
    // {
    //     // Get All Product's Prices -- Start with Product's Final Price
    //     $all_prices = [$this->final_price];

    //     // Get All Product's Points -- Start with Product's Points
    //     $all_points = [];

    //     // Get Free Shipping
    //     $free_shipping = $this->free_shipping;

    //     // Get All Subcategories
    //     $subcategories = $this->subcategories ? $this->subcategories->map(fn($subcategory) => $subcategory->id) : [];

    //     // Get All Categories
    //     $categories = count($subcategories) ? $this->subcategories->map(fn($subcategory) => $subcategory->category) : [];

    //     // Get All Supercategories
    //     $supercategories = count($categories) ? $categories->map(fn($category) => $category->supercategory) : [];

    //     // Get All Offers Ids
    //     $offers_ids = [];

    //     // Get Final Prices From Direct Offers
    //     $direct_offers = $this->validOffers->map(fn($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]);
    //     foreach ($direct_offers as $offer) {

    //         if ($offer['free_shipping']) {
    //             $free_shipping = 1;
    //             $offers_ids[] = $offer['offer_id'];
    //         }

    //         // Percentage Offer
    //         if ($offer['type'] == 0) {
    //             $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
    //             $offers_ids[] = $offer['offer_id'];
    //         }
    //         // Fixed Offer
    //         elseif ($offer['type'] == 1) {
    //             if ($this->final_price >  $offer['value']) {
    //                 $all_prices[] = round($this->final_price - $offer['value'], 2);
    //             } else {
    //                 $all_prices[] = 0;
    //             }
    //             $offers_ids[] = $offer['offer_id'];
    //         }
    //         // Points Offer
    //         elseif ($offer['type'] == 2) {
    //             $all_points[] = $offer['value'];
    //             $offers_ids[] = $offer['offer_id'];
    //         }
    //     }

    //     // Get Final Prices From Offers Through Subcategories
    //     $subcategories_offers = $this->subcategories ? $this->subcategories->map(fn($subcategory) => $subcategory->validOffers->map(fn($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]))->toArray() : [];
    //     foreach ($subcategories_offers as $subcategory) {
    //         foreach ($subcategory as $offer) {
    //             if ($offer['free_shipping']) {
    //                 $free_shipping = 1;
    //                 $offers_ids[] = $offer['offer_id'];
    //             }

    //             if ($offer['type'] == 0) {
    //                 $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
    //                 $offers_ids[] = $offer['offer_id'];
    //             } elseif ($offer['type'] == 1) {
    //                 if ($this->final_price >  $offer['value']) {
    //                     $all_prices[] = round($this->final_price - $offer['value'], 2);
    //                 } else {
    //                     $all_prices[] = 0;
    //                 }
    //                 $offers_ids[] = $offer['offer_id'];
    //             } elseif ($offer['type'] == 2) {
    //                 $all_points[] = $offer['value'];
    //                 $offers_ids[] = $offer['offer_id'];
    //             }
    //         }
    //     }

    //     // Get Final Prices From Offers Through Categories
    //     $categories_offers = $categories ? $categories->map(fn($category) => $category->validOffers->map(fn($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]))->toArray() : [];

    //     foreach ($categories_offers as $category) {
    //         foreach ($category as $offer) {

    //             if ($offer['free_shipping']) {
    //                 $free_shipping = 1;
    //                 $offers_ids[] = $offer['offer_id'];
    //             }

    //             if ($offer['type'] == 0) {
    //                 $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
    //                 $offers_ids[] = $offer['offer_id'];
    //             } elseif ($offer['type'] == 1) {
    //                 if ($this->final_price >  $offer['value']) {
    //                     $all_prices[] = round($this->final_price - $offer['value'], 2);
    //                 } else {
    //                     $all_prices[] = 0;
    //                 }
    //                 $offers_ids[] = $offer['offer_id'];
    //             } elseif ($offer['type'] == 2) {
    //                 $all_points[] = $offer['value'];
    //                 $offers_ids[] = $offer['offer_id'];
    //             }
    //         }
    //     }

    //     // Get Final Prices From Offers Through Supercategories
    //     $supercategories_offers = $supercategories ? $supercategories->map(fn($supercategory) => $supercategory->validOffers->map(fn($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type])) : [];
    //     foreach ($supercategories_offers as $supercategory) {
    //         foreach ($supercategory as $offer) {
    //             if ($offer['free_shipping']) {
    //                 $free_shipping = 1;
    //                 $offers_ids[] = $offer['offer_id'];
    //             }

    //             if ($offer['type'] == 0) {
    //                 $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
    //                 $offers_ids[] = $offer['offer_id'];
    //             } elseif ($offer['type'] == 1) {
    //                 if ($this->final_price >  $offer['value']) {
    //                     $all_prices[] = round($this->final_price - $offer['value'], 2);
    //                 } else {
    //                     $all_prices[] = 0;
    //                 }
    //                 $offers_ids[] = $offer['offer_id'];
    //             } elseif ($offer['type'] == 2) {
    //                 $all_points[] = $offer['value'];
    //                 $offers_ids[] = $offer['offer_id'];
    //             }
    //         }
    //     }

    //     // Get Final Prices From Offers Through Brands
    //     $brand_offers = $this->brand ? $this->brand->validOffers->map(fn($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]) : [];
    //     foreach ($brand_offers as $offer) {
    //         if ($offer['free_shipping']) {
    //             $free_shipping = 1;
    //             $offers_ids[] = $offer['offer_id'];
    //         }

    //         if ($offer['type'] == 0) {
    //             $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
    //             $offers_ids[] = $offer['offer_id'];
    //         } elseif ($offer['type'] == 1) {
    //             if ($this->final_price >  $offer['value']) {
    //                 $all_prices[] = round($this->final_price - $offer['value'], 2);
    //             } else {
    //                 $all_prices[] = 0;
    //             }
    //             $offers_ids[] = $offer['offer_id'];
    //         } elseif ($offer['type'] == 2) {
    //             $all_points[] = $offer['value'];
    //             $offers_ids[] = $offer['offer_id'];
    //         }
    //     }

    //     // Get Best Points Offer
    //     $best_points = count($all_points) ? max($all_points) : 0;

    //     // Get Best Price Offer
    //     $best_price = count($all_prices) ? min($all_prices) : 0;

    //     return [
    //         'best_price' => $best_price,
    //         'best_points' => count($all_points) ? $best_points + $this->points : $this->points,
    //         'free_shipping' =>  $free_shipping,
    //         'offers_ids' => array_unique($offers_ids),
    //     ];
    // }


    public function getBestOfferAttribute()
    {
        $allPrices = [$this->final_price];
        $allPoints = [];
        $freeShipping = $this->free_shipping;
        $offersIds = [];

        $this->processOffers($this->validOffers, $allPrices, $allPoints, $freeShipping, $offersIds);
        $this->processOffers($this->subcategories->flatMap->validOffers, $allPrices, $allPoints, $freeShipping, $offersIds);
        $this->processOffers($this->categories->flatMap->validOffers, $allPrices, $allPoints, $freeShipping, $offersIds);
        $this->processOffers($this->supercategories->flatMap->validOffers, $allPrices, $allPoints, $freeShipping, $offersIds);
        $this->processOffers($this->brand->validOffers ?? [], $allPrices, $allPoints, $freeShipping, $offersIds);

        $bestPrice = count($allPrices) ? min($allPrices) : 0;
        $bestPoints = count($allPoints) ? max($allPoints) + $this->points : $this->points;

        return [
            'best_price' => $bestPrice,
            'best_points' => $bestPoints,
            'free_shipping' => $freeShipping,
            'offers_ids' => array_unique($offersIds),
        ];
    }

    protected function processOffers($offers, &$allPrices, &$allPoints, &$freeShipping, &$offersIds)
    {
        foreach ($offers as $offer) {
            if ($offer->pivot->free_shipping) {
                $freeShipping = 1;
            }

            switch ($offer->pivot->type) {
                case 0: // Percentage
                    $allPrices[] = round($this->final_price - (($offer->pivot->value / 100) * $this->final_price), 2);
                    break;
                case 1: // Fixed
                    $allPrices[] = max(0, round($this->final_price - $offer->pivot->value, 2));
                    break;
                case 2: // Points
                    $allPoints[] = $offer->pivot->value;
                    break;
            }

            $offersIds[] = $offer->id;
        }
    }

    public function type(): Attribute
    {
        return new Attribute(
            get: fn() => "Product"
        );
    }
    ############# Appends :: End #############

    ############# Scopes :: Start #############
    public function scopePublishedProduct($query)
    {
        $now = Carbon::now('Africa/Cairo')->toDateTimeString();

        $query->select(
            [
                'products.id',
                'name',
                'slug',
                'quantity',
                'weight',
                'original_price',
                'base_price',
                'final_price',
                'refundable',
                'points',
                'description',
                'model',
                'free_shipping',
                'publish',
                'under_reviewing',
                'brand_id',
                'final_price',
                'created_at'
            ]
        )
            ->with(
                [
                    'specs',
                    'thumbnail',
                    'offers' => fn($q) => $q->active($now),
                    'brand' => fn($q) => $q->with([
                        'offers' => fn($q) => $q->active($now),
                    ]),
                    'subcategories' => fn($q) => $q
                        ->select('subcategories.id', 'subcategories.name', 'subcategories.category_id')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'subcategories.category' => fn($q) => $q
                        ->select('categories.id', 'categories.name', 'categories.supercategory_id')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'subcategories.category.supercategory' => fn($q) => $q
                        ->select('supercategories.id', 'supercategories.name')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'reviews' => fn($q) => $q->where('status', 1),
                    'coupons'
                ]
            )
            ->where('under_reviewing', 0)
            ->where('publish', 1);
    }

    public function scopePublishedProducts($query, $productsIds)
    {
        $now = Carbon::now('Africa/Cairo')->toDateTimeString();

        $query->select(
            [
                'products.id',
                'name',
                'slug',
                'quantity',
                'weight',
                'original_price',
                'base_price',
                'final_price',
                'refundable',
                'points',
                'description',
                'model',
                'free_shipping',
                'publish',
                'under_reviewing',
                'brand_id',
                'created_at'
            ]
        )
            ->without(['orders'])
            ->with(
                [
                    'specs',
                    'thumbnail',
                    'offers' => fn($q) => $q->active($now),
                    'brand' => fn($q) => $q->with([
                        'offers' => fn($q) => $q->active($now),
                    ]),
                    'subcategories' => fn($q) => $q
                        ->select('subcategories.id', 'subcategories.name', 'subcategories.category_id')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'subcategories.category' => fn($q) => $q
                        ->select('categories.id', 'categories.name',    'categories.supercategory_id')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'subcategories.category.supercategory' => fn($q) => $q
                        ->select('supercategories.id', 'supercategories.name')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'reviews' => fn($q) => $q->where('status', 1),
                    'coupons'
                ]
            )
            ->whereIn('id', $productsIds)
            ->where('under_reviewing', 0)
            ->where('publish', 1);
    }

    public function scopeProductsDetails($query, $productsIds)
    {
        $now = Carbon::now('Africa/Cairo')->toDateTimeString();

        $query->select(
            [
                'products.id',
                'name',
                'slug',
                'quantity',
                'weight',
                'original_price',
                'base_price',
                'final_price',
                'refundable',
                'points',
                'description',
                'model',
                'free_shipping',
                'publish',
                'under_reviewing',
                'brand_id',
                'created_at'
            ]
        )
            ->without(['orders'])
            ->with(
                [
                    'specs',
                    'thumbnail',
                    'offers' => fn($q) => $q->active($now),
                    'brand' => fn($q) => $q->with([
                        'offers' => fn($q) => $q->active($now),
                    ]),
                    'subcategories' => fn($q) => $q
                        ->select('subcategories.id', 'subcategories.name', 'subcategories.category_id')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'subcategories.category' => fn($q) => $q
                        ->select('categories.id', 'categories.name', 'categories.supercategory_id')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'subcategories.category.supercategory' => fn($q) => $q
                        ->select('supercategories.id', 'supercategories.name')
                        ->with([
                            'offers' => fn($q) => $q->active($now),
                        ]),
                    'reviews' => fn($q) => $q->where('status', 1),
                    'coupons'
                ]
            )
            ->whereIn('id', $productsIds);
    }

    public function scopeWhereHasValidOffers($query)
    {
        $now = Carbon::now('Africa/Cairo')->format('Y-m-d H:i');

        return $query->whereHas('offers', function ($query) use ($now) {
            $query->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", $now)
                ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", $now);
        });
    }
    ############# Scopes :: End #############
}
