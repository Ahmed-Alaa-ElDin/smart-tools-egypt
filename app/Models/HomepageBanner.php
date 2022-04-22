<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomepageBanner extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['banner_name'];

    protected $fillable = [
        'banner_name',
        'description',
        'active'
    ];
}
