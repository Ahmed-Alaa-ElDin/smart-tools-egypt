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
        'coupon_discount',
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
        return $this->belongsToMany(Status::class, 'order_status', 'order_id', 'status_id')->withTimestamps();
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price')->withTimestamps();
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
        return $this->status_id == 7 && $this->created_at->diffInDays() <= config('constants.constants.RETURN_PERIOD');
    }
}
