<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressZone extends Model
{
    use HasFactory;

    protected $table = 'address_zone';

    protected $fillable = [
        'country_id',
        'governorate_id',
        'city_id',
        'zone_id',
    ];
}
