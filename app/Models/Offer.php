<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Offer extends Model
{
    use HasRelationships;
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
        return $this->morphedByMany(Supercategory::class, 'offerable')->withPivot('number', 'value', 'type');
    }

    // many to many relationship (polymorphic) (inverse)  Category --> Offers
    public function categories()
    {
        return $this->morphedByMany(Category::class, 'offerable')->withPivot('number', 'value', 'type');
    }


    // many to many relationship (polymorphic) (inverse)  Subcategory --> Offers
    public function subcategories()
    {
        return $this->morphedByMany(Subcategory::class, 'offerable')->withPivot('number', 'value', 'type');
    }


    // many to many relationship (polymorphic) (inverse)  Brand --> Offers
    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'offerable')->withPivot('number', 'value', 'type');
    }


    // many to many relationship (polymorphic) (inverse)  Product --> Offers
    public function products()
    {
        return $this->morphedByMany(Product::class, 'offerable')->withPivot('number', 'value', 'type');
    }

    // many to many Deep relationship  Offer --> Products
    public function directProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->withPivot('offerables', ['number', 'value', 'type']);
    }

    // many to many Deep relationship  Offer --> Super-Category --> Products
    public function supercategoryProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Supercategory::class, Category::class, Subcategory::class, 'product_subcategory'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->withPivot('offerables', ['number', 'value', 'type']);
    }

    // many to many Deep relationship  Offer --> Category --> Products
    public function categoryProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Category::class, Subcategory::class, 'product_subcategory'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->withPivot('offerables', ['number', 'value', 'type']);
    }

    // many to many Deep relationship  Offer --> Sub-Category --> Products
    public function subcategoryProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Subcategory::class, 'product_subcategory'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->withPivot('offerables', ['number', 'value', 'type']);
    }

    // many to many Deep relationship  Offer --> Brand --> Products
    public function brandProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Brand::class],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->withPivot('offerables', ['number', 'value', 'type']);
    }

    // Many to many relationship  Sections --> Offers
    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }
}
