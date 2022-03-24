<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo_path',
        'country_id',
        'meta_title',
        'meta_description',
    ];

    // One to many relationship  Brand --> Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
