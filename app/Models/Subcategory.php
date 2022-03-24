<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Subcategory extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'category_id',
        'meta_title',
        'meta_description',
    ];

    // One to many relationship (Reverse)  Category --> Sub-categories
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
