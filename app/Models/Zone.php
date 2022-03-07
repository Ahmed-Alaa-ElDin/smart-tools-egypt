<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Zone extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'delivery_id',
        'min_size',
        'min_charge',
        'kg_charge',
        'is_active',
    ];

    // One to many relationship (reverse) Delivery --> Zones
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    // Many to Many relationship  Zone --> Countries
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'address_zone', 'zone_id', 'country_id');
    }

    // Many to Many relationship  Zone --> Governorates
    public function governorates()
    {
        return $this->belongsToMany(Governorate::class, 'address_zone', 'zone_id', 'governorate_id');
    }

    // Many to Many relationship  Zone --> Cities
    public function cities()
    {
        return $this->belongsToMany(City::class, 'address_zone', 'zone_id', 'city_id');
    }

    // One to many relationship Zone --> Destinations
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
