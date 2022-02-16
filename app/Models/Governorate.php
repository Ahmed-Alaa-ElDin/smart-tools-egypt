<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Governorate extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'country_id',
    ];

    // One to many relationship  Governorate --> Cities
    public function cities()
    {
        return $this->hasMany(Governorate::class);
    }

    // One to many relationship (Reverse)  Country --> Governorates
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
