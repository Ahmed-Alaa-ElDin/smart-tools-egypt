<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NavLink extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'url',
        'active'
    ];
}
