<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductSpec extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'product_id',
        'title',
        'value',
    ];

    public $translatable = [
        'title',
        'value',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
