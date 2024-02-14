<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsliderSmallBanner extends Model
{
    use HasFactory;

    protected $fillable = ['banner_id', 'rank'];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
