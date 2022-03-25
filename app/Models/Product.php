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

    public $translatable = ['name', 'description', 'meta_title', 'meta_description'];

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
        'subcategory_id',
    ];

    // One to many relationship (Reverse)  Brand --> Products
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // One to many relationship (Reverse)  User --> Products
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // One to many relationship (Reverse)  User --> Products
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    // One to many relationship Product --> Image
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // One to one relationship Product --> Thumbnail
    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class,'product_id')->where('is_thumbnail',1);
    }
}
