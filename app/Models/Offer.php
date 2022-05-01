<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Offer extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title'];

    protected $fillable = [
        'title',
        'banner',
        'free_shipping',
        'start_at',
        'expire_at',
        'value',
        'type',
        'on_orders',
        'number',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    // many to many relationship (polymorphic) (inverse)  Super-Category --> Offers
    public function supercategories()
    {
        return $this->morphedByMany(Supercategory::class, 'offerable')->withPivot('number', 'value','type');
    }

    // many to many relationship (polymorphic) (inverse)  Category --> Offers
    public function categories()
    {
        return $this->morphedByMany(Category::class, 'offerable')->withPivot('number', 'value','type');
    }


    // many to many relationship (polymorphic) (inverse)  Subcategory --> Offers
    public function subcategories()
    {
        return $this->morphedByMany(Subcategory::class, 'offerable')->withPivot('number', 'value','type');
    }


    // many to many relationship (polymorphic) (inverse)  Brand --> Offers
    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'offerable')->withPivot('number', 'value','type');
    }


    // many to many relationship (polymorphic) (inverse)  Product --> Offers
    public function products()
    {
        return $this->morphedByMany(Product::class, 'offerable')->withPivot('number', 'value','type');
    }
}
