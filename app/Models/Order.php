<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'address_id',
        'phone1',
        'phone2',
        'status_id',
        'num_of_items',
        'allow_opening',
        'zone_id',
        'coupon_id',
        'items_points',
        'offers_items_points',
        'offers_order_points',
        'coupon_items_points',
        'coupon_order_points',
        'gift_points',
        'total_weight',
        'tracking_number',
        'package_type',
        'package_desc',
        'order_delivery_id',
        'notes',
        'delivered_at',
        'old_order_id',
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

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'orderable')->withPivot(
            'quantity',
            'price',
            'points',
            'coupon_discount',
            'coupon_points'
        )->withTimestamps();
    }

    public function collections()
    {
        return $this->morphedByMany(Collection::class, 'orderable')->withPivot(
            'quantity',
            'price',
            'points',
            'coupon_discount',
            'coupon_points'
        )->withTimestamps();
    }

    public function invoiceRequests()
    {
        return $this->hasMany(InvoiceRequest::class);
    }

    public function getCanReturnedAttribute()
    {
        if ($this->delivered_at) {
            return $this->status_id == 45 && Carbon::create($this->delivered_at)->diffInDays() <= config('constants.constants.RETURN_PERIOD');
        }
        return false;
    }
}
