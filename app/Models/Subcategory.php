<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Znck\Eloquent\Traits\BelongsToThrough;

class Subcategory extends Model
{
    use BelongsToThrough;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'category_id',
        'meta_title',
        'meta_description',
    ];

    // One to many relationship (Inverse)  Category --> Sub-categories
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // One to many relationship through (Inverse)  Supercategoory --> Sub-categories
    public function supercategory()
    {
        return $this->belongsToThrough(Supercategory::class, Category::class);
    }

    // Many to many relationship  Subcategories --> Products
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    // many to many relationship (polymorphic)  Sub-Category --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }
}
