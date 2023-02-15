<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'value',
        'status',
    ];

    // One to many relationship (reverse) User -> Points
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One to many relationship (reverse) Order -> Points
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

