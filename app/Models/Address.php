<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country_id',
        'governorate_id',
        'city_id',
        'details',
        'special_marque'
    ];

    // One to many relationship (Reverse)  Country --> Addresses
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // One to many relationship (Reverse)  Governorate --> Addresses
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    // One to many relationship (Reverse)  City --> Addresses
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // One to many relationship (Reverse)  User --> Addresses
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
