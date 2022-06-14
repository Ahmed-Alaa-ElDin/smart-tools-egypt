<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        "title",
        "type",
        "active",
        "rank",
        "today_deals",
        "offer_id",
    ];

    public $translatable = ['title'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    // Many to many relationship  Sections --> Products
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    // Many to many relationship  Sections --> Banners
    public function banners()
    {
        return $this->belongsToMany(Banner::class);
    }

    // Many to many relationship  Sections --> Offers
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
