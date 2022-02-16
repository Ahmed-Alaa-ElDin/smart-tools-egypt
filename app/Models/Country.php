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

}
