<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Governorate extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'country_id',
        'bosta_id'
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    // One to many relationship  Governorate --> Cities
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // One to many relationship (Inverse)  Country --> Governorates
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // One to many relationship  Governorate --> Addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Has many through relationship  Governorate --> users
    public function users()
    {
        return $this->belongsToMany(User::class, 'addresses');
    }

    // Has many through relationship  Governorate --> users
    public function customers()
    {
        return $this->belongsToMany(User::class, 'addresses', 'governorate_id', 'user_id')->where(
            function ($q) {
                return $q->whereHas('roles', fn ($q) => $q->where('name', 'Customer'))->orWhereDoesntHave('roles');
            }
        )->distinct('users.id');
    }

    // Has many through relationship  Governorate --> Deliveries
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'destinations')->distinct('deliveries.id');
    }
}
