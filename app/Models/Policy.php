<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Policy extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        "title",
        "content",
    ];

    protected $translatable = [
        "title",
        "content",
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
