<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'user_id',
        'rating',
        'comment',
        'status',
    ];

    public function reviewable()
    {
        return $this->morphTo();
    }

    // TODO:: One to many relationship (Inverse) Product --> Reviews (Apply the same for Collections)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // One to many relationship (Inverse) User --> Reviews
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get approved reviews
    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    // Get pending reviews
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    // Get rejected reviews
    public function scopeRejected($query)
    {
        return $query->where('status', 2);
    }
}
