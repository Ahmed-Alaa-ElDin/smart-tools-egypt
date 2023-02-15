<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
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
        return $this->transactions()->pluck('payment_method');
    }

    public function getMainPaymentMethodAttribute()
    {
        return $this->transactions()->whereIn('payment_method',[1,2,3,4])->count() ? $this->transactions()->whereIn('payment_method',[1,2,3,4])->first()->payment_method : null;
    }

    public function getPaidAttribute(){
        return $this->transactions()->where('payment_status',2)->sum('payment_amount');
    }
}
