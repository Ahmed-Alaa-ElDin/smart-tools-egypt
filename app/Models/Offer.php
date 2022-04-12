<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'banner',
        'free_shipping',
        'start_at',
        'expire_at',
    ];

    // many to many relationship (polymorphic) (inverse)  Super-Category --> Offers
    public function supercategories()
    {
        return $this->morphedByMany(Supercategory::class, 'offerable');
    }

    // many to many relationship (polymorphic) (inverse)  Category --> Offers
    public function categories()
    {
        return $this->morphedByMany(Category::class, 'offerable');
    }


    // many to many relationship (polymorphic) (inverse)  Subcategory --> Offers
    public function subcategories()
    {
        return $this->morphedByMany(Subcategory::class, 'offerable');
    }


    // many to many relationship (polymorphic) (inverse)  Brand --> Offers
    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'offerable');
    }


    // many to many relationship (polymorphic) (inverse)  Product --> Offers
    public function products()
    {
        return $this->morphedByMany(Product::class, 'offerable');
    }
}
