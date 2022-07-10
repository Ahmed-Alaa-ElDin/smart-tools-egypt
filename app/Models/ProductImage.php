<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'product_id',
        'is_thumbnail',
        'featured',
    ];

    // One to many relationship (Inverse) Product --> Images
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeThumbnail($query)
    {
        return $query->where('is_thumbnail',1)->first();
    }

}
