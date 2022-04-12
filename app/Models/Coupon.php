<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'couponable_type',
        'couponable_id',
        'code',
        'value',
        'type',
        'number',
        'on_orders',
        'expire_at',
    ];

    // many to many relationship (polymorphic) (inverse)  Super-Category --> Coupons
    public function supercategories()
    {
        return $this->morphedByMany(Supercategory::class, 'couponable');
    }

    // many to many relationship (polymorphic) (inverse)  Category --> Coupons
    public function categories()
    {
        return $this->morphedByMany(Category::class, 'couponable');
    }


    // many to many relationship (polymorphic) (inverse)  Subcategory --> Coupons
    public function subcategories()
    {
        return $this->morphedByMany(Subcategory::class, 'couponable');
    }


    // many to many relationship (polymorphic) (inverse)  Brand --> Coupons
    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'couponable');
    }


    // many to many relationship (polymorphic) (inverse)  Product --> Coupons
    public function products()
    {
        return $this->morphedByMany(Product::class, 'couponable');
    }
}
