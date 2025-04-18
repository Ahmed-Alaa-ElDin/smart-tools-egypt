<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
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
        'video',
        'barcode',
        'weight',
        'original_price',
        'base_price',
        'final_price',
        'points',
        'description',
        'model',
        'meta_keywords',
        'refundable',
        'free_shipping',
        'publish',
        'under_reviewing',
        'created_by',
    ];

    protected $appends = [
        "avg_rating",
        "can_review",
        "quantity",
        'type',
        'has_pending_notification'
    ];

    protected $with = ['reviews', 'orders', 'products'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    // One to many relationship (Inverse)  User --> Collections
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // One to many relationship (polymorphic) Collection --> Images
    public function images()
    {
        return $this->morphMany(Image::class, "imagable");
    }

    // One to one relationship (polymorphic) Collection --> Thumbnail
    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imagable')->where('is_thumbnail', 1);
    }

    // many to many relationship (polymorphic)  Collection --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }

    // Many to many relationship (polymorphic) Sections --> Collections
    public function sections()
    {
        return $this->morphToMany(Section::class, 'sectionable')->withPivot(['rank']);
    }

    // many to many relationship (polymorphic)  Collection --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'offerable')->withPivot([
            'value',
            'type',
        ]);
    }

    // One to many relationship Collection --> Products
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(
            'quantity',
        )->withTimestamps();
    }

    // one to many relationship Collection --> Specs
    public function specs()
    {
        return $this->hasMany(CollectionSpec::class);
    }

    // One to many (Polymorphic) relationship Collection --> Reviews
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // many to many (Polymorphic) relationship Collection --> Orders
    public function orders()
    {
        return $this->morphToMany(Order::class, 'orderable')->withPivot(
            'order_id',
            'quantity',
            'price',
            'points',
            'coupon_discount',
            'coupon_points'
        )->withTimestamps();
    }

    // many to many Relationship Collection --> Related Collections
    public function relatedCollections()
    {
        return $this->belongsToMany(
            Collection::class,
            'collection_collection_related',
            'first_collection_id',
            'second_collection_id'
        )->withPivot('rank');
    }

    // many to many Relationship Collection --> Related Products
    public function relatedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'collection_product_related',
            'collection_id',
            'product_id'
        )->withPivot('rank');
    }

    // many to many Relationship Collection --> Complement Collections
    public function complementedCollections()
    {
        return $this->belongsToMany(
            Collection::class,
            'collection_collection_complemented',
            'first_collection_id',
            'second_collection_id'
        )->withPivot('rank');
    }

    // many to many Relationship Collection --> Complemented Products
    public function complementedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'collection_product_complemented',
            'collection_id',
            'product_id'
        )->withPivot('rank');
    }

    // many to many polymorphic relationship Collection --> Back To Stock Notifications
    public function backToStockNotifications()
    {
        return $this->morphToMany(
            BackToStockNotification::class,
            'notifiable',
            'back_to_stock_notifiables',
            'notifiable_id',
            'notification_id'
        );
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

    public function getQuantityAttribute()
    {
        return $this->products()
            ->without(['reviews', 'orders', 'brand'])->get()
            ->map(fn($product) =>  floor($product->quantity / $product->pivot->quantity))
            ->min() ?? 0;
    }

    public function getTypeAttribute()
    {
        return "Collection";
    }

    protected function hasPendingNotification(): Attribute
    {
        return Attribute::make(
            get: function () {
                $guestPhone = session('guest_phone', null);

                if (auth()->check()) {
                    return $this
                        ->backToStockNotifications()
                        ->where('user_id', auth()->id())
                        ->whereNull('sent_at')
                        ->exists();
                } elseif ($guestPhone !== null) {
                    return $this
                        ->backToStockNotifications()
                        ->where('phone', $guestPhone)
                        ->whereNull('sent_at')
                        ->exists();
                }

                return false;
            },
            // Optional: Add a setter if needed
            set: fn ($value) => $value
        );
    }
    ############# Appends :: End #############


    ############# Scopes :: Start #############
    public function scopePublishedCollection($query)
    {
        $query->select(
            [
                'collections.id',
                'name',
                'slug',
                'collections.original_price',
                'base_price',
                'final_price',
                'refundable',
                'points',
                'description',
                'model',
                'free_shipping',
                'publish',
                'under_reviewing',
                'created_at'
            ]
        )
            ->with(
                [
                    'thumbnail',
                    'offers' => fn($q) => $q
                        ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                        ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i')),
                    'reviews' => fn($q) => $q->where('status', 1),
                    'coupons'
                ]
            )
            ->where('under_reviewing', 0)
            ->where('publish', 1);
    }

    public function scopePublishedCollections($query, $collections_id)
    {
        $query->select(
            [
                'collections.id',
                'name',
                'slug',
                'weight',
                'collections.original_price',
                'base_price',
                'final_price',
                'refundable',
                'points',
                'description',
                'model',
                'free_shipping',
                'publish',
                'under_reviewing',
                'created_at'
            ]
        )
            ->with(
                [
                    // 'products' => fn ($q) => $q->with(['brand' => fn ($q) => $q->with('offers')]),
                    'thumbnail',
                    'offers' => fn($q) => $q
                        ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
                        ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i')),
                    'reviews' => fn($q) => $q->where('status', 1),
                    'coupons'
                ]
            )
            ->whereIn('collections.id', $collections_id)
            ->where('under_reviewing', 0)
            ->where('publish', 1);
    }
    ############# Scopes :: End #############

}
