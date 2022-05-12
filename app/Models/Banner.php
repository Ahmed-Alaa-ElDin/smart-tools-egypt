<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Banner extends Model
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

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    // Many to many relationship  Sections --> Banners
    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

}
