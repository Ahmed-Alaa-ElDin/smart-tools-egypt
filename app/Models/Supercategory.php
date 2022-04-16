<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Supercategory extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'icon',
        'meta_title',
        'meta_description',
    ];

    // One to many relationship  Super-Category --> Categories
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // One to many through relationship  Super-Category --> Sub-Categories
    public function subcategories()
    {
        return $this->hasManyThrough(Subcategory::class, Category::class);
    }

    // many to many relationship (polymorphic)  Super-Category --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class,'couponable');
    }

    // many to many relationship (polymorphic)  Super-Category --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class,'offerable');
    }
}