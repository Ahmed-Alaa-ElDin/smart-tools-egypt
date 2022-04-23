<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomepageBanner extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['description'];

    protected $fillable = [
        'banner_name',
        'description',
        'link',
        'rank',
        'active'
    ];

}
