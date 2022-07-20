<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'phone1',
        'phone2',
        'package_type',
        'package_desc',
        'num_of_items',
        'allow_opening',
        'zone_id',
        'coupon_id',
        'status_id',
        'subtotal_base',
        'subtotal_final',
        'delivery_fees',
        'total_weight',
        'payment_method_id',
        'payment_status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }
}
