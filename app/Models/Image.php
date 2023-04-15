<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagable_type',
        'imagable_id',
        'file_name',
        'is_thumbnail',
        'featured',
    ];

    public function imagable()
    {
        return $this->morphTo();
    }

    public function scopeThumbnail($query)
    {
        return $query->where('is_thumbnail',1)->first();
    }

}
