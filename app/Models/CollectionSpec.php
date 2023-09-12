<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CollectionSpec extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'collection_id',
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

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
