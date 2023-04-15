<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Category extends Model
{
    use HasRelationships;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'top',
        'publish',
        'image_name',
        'supercategory_id',
        'meta_title',
        'meta_description',
    ];

    protected $with = ['validOffers'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    // One to many relationship  Category --> Sub-Categories
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    // One to many relationship (Inverse)  Super-Category --> Categories
    public function supercategory()
    {
        return $this->belongsTo(Supercategory::class);
    }

    // many to many relationship (polymorphic)  Category --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }

    // many to many relationship (polymorphic)  Category --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'offerable')->withPivot([
            'offerable_type',
            'value',
            'type'
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

    // has many through relationship  Category --> Products
    public function products()
    {
        return $this->hasManyDeep(Product::class, [Subcategory::class, 'product_subcategory']);
    }

    // One to many relationship Category --> Image
    public function images()
    {
        return $this->morphMany(Image::class, "imagable");
    }
}
