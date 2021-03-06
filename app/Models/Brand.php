<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'top',
        'logo_path',
        'country_id',
        'meta_title',
        'meta_description',
    ];

    // One to many relationship  Brand --> Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // One to many relationship (Inverse)  Country --> Brands
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // many to many relationship (polymorphic)  Brand --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }

    // many to many relationship (polymorphic)  Brand --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'offerable')->withPivot([
            'offerable_type',
            'value',
            'type',
            'number'
        ]);
    }
}
