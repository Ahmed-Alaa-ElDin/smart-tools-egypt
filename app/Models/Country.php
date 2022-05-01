<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name'
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    // One to many relationship  Country --> Governorates
    public function governorates()
    {
        return $this->hasMany(Governorate::class);
    }

    // One to many relationship  Country --> Addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Has many through relationship  Country --> users
    public function users()
    {
        return $this->belongsToMany(User::class, 'addresses');
    }

    // Has many through relationship  Country --> cities
    public function cities()
    {
        return $this->hasManyThrough(City::class, Governorate::class);
    }


    // Has many through relationship  Country --> Deliveries
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'destinations')->distinct('deliveries.id');
    }

    // One to many relationship  Country --> Brands
    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
}
