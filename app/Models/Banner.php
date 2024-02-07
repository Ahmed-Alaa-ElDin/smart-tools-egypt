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
        'top_banner',
        'slider',
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

    // One to many relationship  Banners --> MainSliders
    public function mainSliderBanner()
    {
        return $this->hasOne(MainSliderBanner::class);
    }

    // One to many relationship  Banners --> SubSliders
    public function subSliderBanner()
    {
        return $this->hasOne(SubsliderBanner::class);
    }

    // Scope Slider
    public function scopeSlider($query)
    {
        return $query->where('slider', 1);
    }

    // Scope Top Banner
    public function scopeTopBanner($query)
    {
        return $query->where('top_banner', 1);
    }
}
