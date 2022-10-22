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
        // 'should_pay',
        // 'should_get',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
