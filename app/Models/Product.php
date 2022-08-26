<?php

namespace App\Models;

use Carbon\Carbon;
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

    public $translatable = ['name', 'description', 'slug'];

    protected $fillable = [
        'name',
        'slug',
        'barcode',
        'weight',
        'quantity',
        'low_stock',
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
        'specs',
        'created_by',
        'brand_id',
        'today_deal',
    ];

    protected $appends = [
        "avg_rating", "can_review",
        //  "best_offer"
    ];

    protected $with = ['reviews', 'orders', 'brand', 'validOffers'];

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

    // Many to many through relationship  Categories --> Products
    public function categories()
    {
        return $this->belongsToThrough(Category::class, Subcategory::class);
    }

    // Many to many through relationship  Categories --> Products
    public function supercategory()
    {
        return $this->belongsToThrough(Supercategory::class, [Category::class, Subcategory::class]);
    }

    // One to many relationship Product --> Image
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // One to one relationship Product --> Thumbnail
    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_thumbnail', 1);
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
            'number'
        ]);
    }

    public function validOffers()
    {
        return $this->morphToMany(Offer::class, 'offerable')
            ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
            ->where(
                fn ($q) => $q
                    ->where('offerables.number', '>', 0)
                    ->orWhereNull('offerables.number')
            )->withPivot([
                'value',
                'type',
                'number'
            ]);
    }

    // Many to many relationship  Sections --> Products
    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    // One to many relationship Product --> Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // many to many relationship Product --> Orders
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot(
            'quantity',
            'price',
            'points',
            'coupon_discount',
            'coupon_points'
        )->withTimestamps();
    }

    ############# Appends :: Start #############
    public function getAvgRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function getCanReviewAttribute()
    {
        return $this->reviews->where('user_id', auth()->id())->count() == 0 && $this->orders->where('user_id', auth()->id())->count() > 0 ? true : false;
    }

    public function getBestOfferAttribute()
    {
        // Get All Product's Prices -- Start with Product's Final Price
        $all_prices = [$this->final_price];

        // Get All Product's Points -- Start with Product's Points
        $all_points = [];

        // Get Free Shipping
        $free_shipping = $this->free_shipping;

        // Get All Subcategories
        $subcategories = $this->subcategories ? $this->subcategories->map(fn ($subcategory) => $subcategory->id) : [];

        // Get All Categories
        $categories = count($subcategories) ? $this->subcategories->map(fn ($subcategory) => $subcategory->category) : [];

        // Get All Supercategories
        $supercategories = count($categories) ? $categories->map(fn ($category) => $category->supercategory) : [];

        // Get All Offers Ids
        $offers_ids = [];

        // Get Final Prices From Direct Offers
        $direct_offers = $this->validOffers->map(fn ($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]);
        foreach ($direct_offers as $offer) {
            if ($offer['number'] == null || $offer['number'] > 0) {

                if ($offer['free_shipping']) {
                    $free_shipping = 1;
                    $offers_ids[] = $offer['offer_id'];
                }

                // Percentage Offer
                if ($offer['type'] == 0) {
                    $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
                    $offers_ids[] = $offer['offer_id'];
                }
                // Fixed Offer
                elseif ($offer['type'] == 1) {
                    if ($this->final_price >  $offer['value']) {
                        $all_prices[] = round($this->final_price - $offer['value'], 2);
                    } else {
                        $all_prices[] = 0;
                    }
                    $offers_ids[] = $offer['offer_id'];
                }
                // Points Offer
                elseif ($offer['type'] == 2) {
                    $all_points[] = $offer['value'];
                    $offers_ids[] = $offer['offer_id'];
                }
            }
        }

        // Get Final Prices From Offers Through Subcategories
        $subcategories_offers = $this->subcategories ? $this->subcategories->map(fn ($subcategory) => $subcategory->validOffers->map(fn ($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]))->toArray() : [];
        foreach ($subcategories_offers as $subcategory) {
            foreach ($subcategory as $offer) {
                if ($offer['number'] == null || $offer['number'] > 0) {
                    if ($offer['free_shipping']) {
                        $free_shipping = 1;
                        $offers_ids[] = $offer['offer_id'];
                    }

                    if ($offer['type'] == 0) {
                        $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
                        $offers_ids[] = $offer['offer_id'];
                    } elseif ($offer['type'] == 1) {
                        if ($this->final_price >  $offer['value']) {
                            $all_prices[] = round($this->final_price - $offer['value'], 2);
                        } else {
                            $all_prices[] = 0;
                        }
                        $offers_ids[] = $offer['offer_id'];
                    } elseif ($offer['type'] == 2) {
                        $all_points[] = $offer['value'];
                        $offers_ids[] = $offer['offer_id'];
                    }
                }
            }
        }

        // Get Final Prices From Offers Through Categories
        $categories_offers = $categories ? $categories->map(fn ($category) => $category->validOffers->map(fn ($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]))->toArray() : [];
        foreach ($categories_offers as $category) {
            foreach ($category as $offer) {
                if ($offer['number'] == null || $offer['number'] > 0) {

                    if ($offer['free_shipping']) {
                        $free_shipping = 1;
                        $offers_ids[] = $offer['offer_id'];
                    }

                    if ($offer['type'] == 0) {
                        $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
                        $offers_ids[] = $offer['offer_id'];
                    } elseif ($offer['type'] == 1) {
                        if ($this->final_price >  $offer['value']) {
                            $all_prices[] = round($this->final_price - $offer['value'], 2);
                        } else {
                            $all_prices[] = 0;
                        }
                        $offers_ids[] = $offer['offer_id'];
                    } elseif ($offer['type'] == 2) {
                        $all_points[] = $offer['value'];
                        $offers_ids[] = $offer['offer_id'];
                    }
                }
            }
        }

        // Get Final Prices From Offers Through Supercategories
        $supercategories_offers = $supercategories ? $supercategories->map(fn ($supercategory) => $supercategory->validOffers->map(fn ($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number])) : [];
        foreach ($supercategories_offers as $supercategory) {
            foreach ($supercategory as $offer) {
                if ($offer['number'] == null || $offer['number'] > 0) {
                    if ($offer['free_shipping']) {
                        $free_shipping = 1;
                        $offers_ids[] = $offer['offer_id'];
                    }

                    if ($offer['type'] == 0) {
                        $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
                        $offers_ids[] = $offer['offer_id'];
                    } elseif ($offer['type'] == 1) {
                        if ($this->final_price >  $offer['value']) {
                            $all_prices[] = round($this->final_price - $offer['value'], 2);
                        } else {
                            $all_prices[] = 0;
                        }
                        $offers_ids[] = $offer['offer_id'];
                    } elseif ($offer['type'] == 2) {
                        $all_points[] = $offer['value'];
                        $offers_ids[] = $offer['offer_id'];
                    }
                }
            }
        }

        // Get Final Prices From Offers Through Brands
        $brand_offers = $this->brand ? $this->brand->validOffers->map(fn ($offer) => ['offer_id' => $offer->id, 'free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]) : [];
        foreach ($brand_offers as $offer) {
            if ($offer['number'] == null || $offer['number'] > 0) {
                if ($offer['free_shipping']) {
                    $free_shipping = 1;
                    $offers_ids[] = $offer['offer_id'];
                }

                if ($offer['type'] == 0) {
                    $all_prices[] = round($this->final_price - (($offer['value'] / 100) * $this->final_price), 2);
                    $offers_ids[] = $offer['offer_id'];
                } elseif ($offer['type'] == 1) {
                    if ($this->final_price >  $offer['value']) {
                        $all_prices[] = round($this->final_price - $offer['value'], 2);
                    } else {
                        $all_prices[] = 0;
                    }
                    $offers_ids[] = $offer['offer_id'];
                } elseif ($offer['type'] == 2) {
                    $all_points[] = $offer['value'];
                    $offers_ids[] = $offer['offer_id'];
                }
            }
        }

        // Get Best Points Offer
        $best_points = count($all_points) ? max($all_points) : 0;

        // Get Best Price Offer
        $best_price = count($all_prices) ? min($all_prices) : 0;

        return [
            'best_price' => $best_price,
            'best_points' => count($all_points) ? $best_points + $this->points : $this->points,
            'free_shipping' =>  $free_shipping,
            'offers_ids' => array_unique($offers_ids),
        ];
    }
    ############# Appends :: End #############

    ############# Scopes :: Start #############
    public function scopePublishedProduct($query)
    {
        $query->select(
            [
                'products.id',
                'name',
                'slug',
                'quantity',
                'weight',
                'base_price',
                'final_price',
                'points',
                'description',
                'model',
                'free_shipping',
                'publish',
                'under_reviewing',
                'brand_id',
                'final_price'
            ]
        )
            ->with(
                [
                    'thumbnail',
                    'offers' => fn ($q) => $q
                        ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                        ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                        ->where(
                            fn ($q) => $q
                                ->where('offerables.number', '>', 0)
                                ->orWhereNull('offerables.number')
                        ),
                    'brand' => fn ($q) => $q->with([
                        'offers' => fn ($q) => $q
                            ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->where(
                                fn ($q) => $q
                                    ->where('offerables.number', '>', 0)
                                    ->orWhereNull('offerables.number')
                            )
                    ]),
                    'subcategories' => fn ($q) => $q->with([
                        'offers' => fn ($q) => $q
                            ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->where(
                                fn ($q) => $q
                                    ->where('offerables.number', '>', 0)
                                    ->orWhereNull('offerables.number')
                            ),
                        'category' => fn ($q) => $q->with([
                            'offers' => fn ($q) => $q
                                ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                ->where(
                                    fn ($q) => $q
                                        ->where('offerables.number', '>', 0)
                                        ->orWhereNull('offerables.number')
                                ),
                            'supercategory' => fn ($q) => $q->with([
                                'offers' => fn ($q) => $q
                                    ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                    ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                    ->where(
                                        fn ($q) => $q
                                            ->where('offerables.number', '>', 0)
                                            ->orWhereNull('offerables.number')
                                    )
                            ])
                        ]),
                    ])
                ]
            )
            ->where('under_reviewing', 0)
            ->where('publish', 1);
    }

    public function scopePublishedProducts($query, $products_id)
    {
        $query->select(
            [
                'products.id',
                'name',
                'slug',
                'quantity',
                'weight',
                'base_price',
                'final_price',
                'points',
                'description',
                'model',
                'free_shipping',
                'publish',
                'under_reviewing',
                'brand_id',
            ]
        )
            ->with(
                [
                    'thumbnail',
                    'offers' => fn ($q) => $q
                        ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                        ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                        ->where(
                            fn ($q) => $q
                                ->where('offerables.number', '>', 0)
                                ->orWhereNull('offerables.number')
                        ),
                    'brand' => fn ($q) => $q->with([
                        'offers' => fn ($q) => $q
                            ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->where(
                                fn ($q) => $q
                                    ->where('offerables.number', '>', 0)
                                    ->orWhereNull('offerables.number')
                            )
                    ]),
                    'subcategories' => fn ($q) => $q->with([
                        'offers' => fn ($q) => $q
                            ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                            ->where(
                                fn ($q) => $q
                                    ->where('offerables.number', '>', 0)
                                    ->orWhereNull('offerables.number')
                            ),
                        'category' => fn ($q) => $q->with([
                            'offers' => fn ($q) => $q
                                ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                ->where(
                                    fn ($q) => $q
                                        ->where('offerables.number', '>', 0)
                                        ->orWhereNull('offerables.number')
                                ),
                            'supercategory' => fn ($q) => $q->with([
                                'offers' => fn ($q) => $q
                                    ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                    ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                                    ->where(
                                        fn ($q) => $q
                                            ->where('offerables.number', '>', 0)
                                            ->orWhereNull('offerables.number')
                                    )
                            ])
                        ]),
                    ]),
                    'reviews' => fn ($q) => $q->where('status', 1),
                    'coupons'
                ]
            )
            ->whereIn('id', $products_id)
            ->where('under_reviewing', 0)
            ->where('publish', 1);
    }
    ############# Scopes :: End #############
}
