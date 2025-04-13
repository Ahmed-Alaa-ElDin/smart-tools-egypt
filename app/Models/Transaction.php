<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'invoice_id',
        'order_id',
        'old_order_id',
        'user_id',
        'payment_amount',
        'payment_method_id',
        'payment_status_id',
        'service_provider_transaction_id',
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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
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

    public function scopeElectronicWallet($query)
    {
        return $query->where('payment_method', 4);
    }
}
