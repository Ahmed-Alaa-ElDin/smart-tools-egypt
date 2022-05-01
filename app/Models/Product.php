<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
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
        return $this->morphToMany(Offer::class, 'offerable');
    }
}
