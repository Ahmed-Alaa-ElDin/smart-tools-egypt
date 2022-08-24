<?php

namespace App\Models;

use Carbon\Carbon;
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
        'coupon_order_discount',
        'coupon_order_points',
        'coupon_products_discount',
        'coupon_products_points',
        'status_id',
        'subtotal_base',
        'subtotal_final',
        'total',
        'should_pay',
        'should_get',
        'used_points',
        'used_balance',
        'gift_points',
        'delivery_fees',
        'total_weight',
        'payment_method',
        'tracking_number',
        'order_delivery_id',
        'notes',
    ];

    protected $appends = ['can_returned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function statuses()
    {
        return $this->belongsToMany(Status::class)->withPivot('id', 'notes')->withTimestamps();
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price', 'points')->withTimestamps();
    }

    public function invoiceRequests()
    {
        return $this->hasMany(InvoiceRequest::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getCanReturnedAttribute()
    {
        if ($this->delivered_at) {
            return $this->status_id == 45 && Carbon::create($this->delivered_at)->diffInDays() <= config('constants.constants.RETURN_PERIOD');
        }
        return false;
    }
}
