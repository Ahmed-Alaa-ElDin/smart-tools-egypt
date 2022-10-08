<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Znck\Eloquent\Traits\BelongsToThrough;

class Collection extends Model
{
    use HasRelationships;
    use BelongsToThrough;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name', 'description', 'slug'];

    protected $fillable = [
        'name',
        'slug',
        'video',
        'barcode',
        'weight',
        'original_price',
        'base_price',
        'final_price',
        'points',
        'description',
        'model',
        'specs',
        'meta_keywords',
        'refundable',
        'free_shipping',
        'publish',
        'under_reviewing',
        'created_by'
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    // One to many relationship (Inverse)  User --> Collections
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // One to many relationship Collection --> Images
    public function images()
    {
        return $this->morphMany(Image::class, "imagable");
    }

    // One to one relationship Collection --> Thumbnail
    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imagable')->where('is_thumbnail', 1);
    }

    // many to many relationship (polymorphic)  Collection --> Coupons
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }

    // Many to many relationship  Sections --> Collections
    public function sections()
    {
        return $this->morphToMany(Section::class, 'sectionable')->withPivot(['rank']);
    }

    // many to many relationship (polymorphic)  Collection --> Offers
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'offerable')->withPivot([
            'value',
            'type',
            'number'
        ]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(
            'quantity',
        )->withTimestamps();
    }
}
