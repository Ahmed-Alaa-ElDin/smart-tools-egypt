<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'supercategory_id',
        'meta_title',
        'meta_description',
    ];

    // One to many relationship  Category --> Sub-Categories
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    // One to many relationship (Inverse)  Super-Category --> Categories
    public function supercategory()
    {
        return $this->belongsTo(Supercategory::class);
    }

    // many to many relationship (polymorphic)  Category --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }

    // many to many relationship (polymorphic)  Category --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'offerable');
    }
}