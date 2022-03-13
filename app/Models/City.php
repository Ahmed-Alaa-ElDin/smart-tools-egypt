<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'governorate_id',
    ];

    // One to many relationship (Reverse)  Governorate --> Cities
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    // One to many relationship  City --> Addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Has many through relationship  City --> users
    public function users()
    {
        return $this->hasManyThrough(User::class, Address::class);
    }

    // Many to Many relationship  City --> Zones
    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'address_zone', 'city_id', 'zone_id');
    }
}
