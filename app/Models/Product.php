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

    public $translatable = ['name', 'description'];

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
        'meta_title',
        'meta_description',
        'free_shipping',
        'publish',
        'under_reviewing',
        'specs',
        'created_by',
        'brand_id',
        'today_deal',
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

    // Many to many relationship  Sections --> Products
    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

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
            ->where('publish', 1);
    }
}
