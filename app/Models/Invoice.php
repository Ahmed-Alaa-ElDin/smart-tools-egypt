<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'subtotal_base',
        'items_discount',
        'offers_items_discount',
        'offers_order_discount',
        'coupon_items_discount',
        'coupon_order_discount',
        'delivery_fees',
        'total',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getPaymentMethodsAttribute()
    {
        return $this->transactions()->pluck('payment_method_id');
    }

    public function getMainPaymentMethodAttribute()
    {
        return $this->transactions()->whereIn('payment_method_id', [
            PaymentMethod::Cash->value,
            PaymentMethod::Card->value,
            PaymentMethod::Installments->value,
            PaymentMethod::VodafoneCash->value,
        ])->count() ?
            $this->transactions()->whereIn('payment_method_id', [
                PaymentMethod::Cash->value,
                PaymentMethod::Card->value,
                PaymentMethod::Installments->value,
                PaymentMethod::VodafoneCash->value,
            ])->first()->payment_method_id : null;
    }

    public function getPaidAttribute(){
        return $this->transactions()->where('payment_status_id',2)->sum('payment_amount');
    }
}
