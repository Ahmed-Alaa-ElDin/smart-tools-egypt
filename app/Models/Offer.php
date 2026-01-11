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

    const TYPE_PERCENTAGE = 0;
    const TYPE_FIXED = 1;
    const TYPE_POINTS = 2;

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
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    // many to many relationship (polymorphic) (inverse)  Super-Category --> Offers
    public function supercategories()
    {
        return $this->morphedByMany(Supercategory::class, 'offerable')->withPivot('value', 'type');
    }

    // many to many relationship (polymorphic) (inverse)  Category --> Offers
    public function categories()
    {
        return $this->morphedByMany(Category::class, 'offerable')->withPivot('value', 'type');
    }


    // many to many relationship (polymorphic) (inverse)  Subcategory --> Offers
    public function subcategories()
    {
        return $this->morphedByMany(Subcategory::class, 'offerable')->withPivot('value', 'type');
    }


    // many to many relationship (polymorphic) (inverse)  Brand --> Offers
    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'offerable')->withPivot('value', 'type');
    }

    // many to many relationship (polymorphic) (inverse)  Product --> Offers
    public function products()
    {
        return $this->morphedByMany(Product::class, 'offerable')->withPivot('value', 'type');
    }

    // many to many relationship (polymorphic) (inverse)  Collection --> Offers
    public function collections()
    {
        return $this->morphedByMany(Collection::class, 'offerable')->withPivot('value', 'type');
    }

    // many to many Deep relationship  Offer --> Collections
    public function directCollections()
    {
        return $this->hasManyDeep(
            Collection::class,
            ['offerables'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )
            ->select(['collections.id', 'collections.publish'])
            ->withPivot('offerables', ['value', 'type']);
    }

    // many to many Deep relationship  Offer --> Products
    public function directProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )
            ->select(['products.id', 'products.publish'])
            ->withPivot('offerables', ['value', 'type']);
    }

    // many to many Deep relationship  Offer --> Super-Category --> Products
    public function supercategoryProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Supercategory::class, Category::class, Subcategory::class, 'product_subcategory'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->select(['products.id', 'products.publish'])->withPivot('offerables', ['value', 'type']);
    }

    // many to many Deep relationship  Offer --> Category --> Products
    public function categoryProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Category::class, Subcategory::class, 'product_subcategory'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->select(['products.id', 'products.publish'])->withPivot('offerables', ['value', 'type']);
    }

    // many to many Deep relationship  Offer --> Sub-Category --> Products
    public function subcategoryProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Subcategory::class, 'product_subcategory'],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->select(['products.id', 'products.publish'])->withPivot('offerables', ['value', 'type']);
    }

    // many to many Deep relationship  Offer --> Brand --> Products
    public function brandProducts()
    {
        return $this->hasManyDeep(
            Product::class,
            ['offerables', Brand::class],
            [null, 'id'],
            [null, ['offerable_type', 'offerable_id']]
        )->select(['products.id', 'products.publish', 'products.brand_id'])->withPivot('offerables', ['value', 'type']);
    }

    // Many to many relationship  Sections --> Offers
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    // Scope order's offers
    public function scopeOrderOffers($query)
    {
        return $query->where('on_orders', 1)->where('start_at', '<=', now())->where('expire_at', '>=', now());
    }

    public function scopeActive($query, $date)
    {
        return $query->where('start_at', '<', $date)
            ->where('expire_at', '>', $date);
    }
}
