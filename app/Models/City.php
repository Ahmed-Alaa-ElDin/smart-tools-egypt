<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'governorate_id',
    ];

    // One to many relationship (Reverse)  Governorate --> Cities
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
