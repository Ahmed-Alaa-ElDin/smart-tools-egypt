<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
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
        return $this->transactions->pluck('payment_method_id');
    }

    public function getMainPaymentMethodAttribute()
    {
        return $this->transactions->whereIn('payment_method_id', [
            PaymentMethod::Cash->value,
            PaymentMethod::Card->value,
            PaymentMethod::Installments->value,
            PaymentMethod::ElectronicWallet->value,
        ])->count() ?
            $this->transactions->whereIn('payment_method_id', [
                PaymentMethod::Cash->value,
                PaymentMethod::Card->value,
                PaymentMethod::Installments->value,
                PaymentMethod::ElectronicWallet->value,
            ])->first()->payment_method_id : null;
    }

    public function getPaidAttribute(){
        return $this->transactions->where('payment_status_id',PaymentStatus::Paid->value)->sum('payment_amount') + $this->transactions->where('payment_status_id',PaymentStatus::Refunded->value)->sum('payment_amount');
    }

    public function getUnpaidAttribute (){
        return $this->transactions->where('payment_status_id',PaymentStatus::Pending->value)->sum('payment_amount');
    }

    public function getRefundedAttribute (){
        return $this->transactions->where('payment_status_id',PaymentStatus::Refunded->value)->sum('payment_amount');
    }

    public function getRefundableAttribute (){
        return $this->transactions->where('payment_status_id',PaymentStatus::Refundable->value)->sum('payment_amount');
    }
}
