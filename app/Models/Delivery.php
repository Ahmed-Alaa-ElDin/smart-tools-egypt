<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Delivery extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'logo_path',
        'is_active',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    // One to many relationship  Delivery --> Zones
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    // One to many relationship  Delivery --> Zones
    public function destinations()
    {
        return $this->hasManyThrough(Destination::class, Zone::class);
    }

    // One to many relationship  Delivery --> Countries
    public function countries()
    {
        return $this->belongsToMany(Country::class, Destination::class);
    }

    // One to many relationship  Delivery --> Countries
    public function governorates()
    {
        return $this->belongsToMany(Governorate::class, Destination::class);
    }

    // One to many relationship  Delivery --> Cities
    public function cities()
    {
        return $this->belongsToMany(City::class, Destination::class);
    }

    // One to many relationship  Delivery --> phones
    public function phones()
    {
        return $this->hasMany(DeliveryPhone::class);
    }
}
