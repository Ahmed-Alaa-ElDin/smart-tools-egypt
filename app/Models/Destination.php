<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'country_id',
        'governorate_id',
        'city_id',
        'zone_id',
    ];

    // One to many relationship (reverse) Zone --> Destinations
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    // One to many relationship (reverse) Delivery --> Destinations
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
