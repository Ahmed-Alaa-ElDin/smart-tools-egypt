<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'old_order_id',
        'user_id',
        'payment_amount',
        'payment_method',
        'payment_status',
        'paymob_order_id',
        'payment_details',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 1);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 2);
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 3);
    }

    public function scopeCash($query)
    {
        return $query->where('payment_method', 1);
    }

    public function scopeCard($query)
    {
        return $query->where('payment_method', 2);
    }

    public function scopeInstallments($query)
    {
        return $query->where('payment_method', 3);
    }

    public function scopeVodafoneCash($query)
    {
        return $query->where('payment_method', 4);
    }
}
