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
        return $this->belongsToMany(User::class, 'addresses');
    }

    // Has many through relationship  City --> Deliveries
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'destinations')->distinct('deliveries.id');
    }
}
