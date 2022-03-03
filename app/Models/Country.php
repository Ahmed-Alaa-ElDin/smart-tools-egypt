<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name'
    ];

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
        return $this->hasManyThrough(User::class, Address::class);
    }

    // Many to Many relationship  Country --> Zones
    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'address_zone', 'country_id', 'zone_id');
    }
}
