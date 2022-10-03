<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Znck\Eloquent\Traits\BelongsToThrough;

class Subcategory extends Model
{
    use BelongsToThrough;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'category_id',
        'publish',
        'meta_title',
        'meta_description',
        'top',
        'image_name'
    ];

    protected $with = ['validOffers'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    // One to many relationship (Inverse)  Category --> Sub-categories
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // One to many relationship through (Inverse)  Supercategoory --> Sub-categories
    public function supercategory()
    {
        return $this->belongsToThrough(Supercategory::class, Category::class);
    }

    // Many to many relationship  Subcategories --> Products
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    // many to many relationship (polymorphic)  Sub-Category --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }

    // many to many relationship (polymorphic)  Sub-Category --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'offerable')
            ->withPivot([
                'offerable_type',
                'value',
                'type',
            ]);
    }

    public function validOffers()
    {
        return $this->morphToMany(Offer::class, 'offerable')
            ->whereRaw("start_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
            ->withPivot([
                'value',
                'type',
            ]);
    }
}
