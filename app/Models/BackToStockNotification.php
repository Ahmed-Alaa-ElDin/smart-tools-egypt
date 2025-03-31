<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackToStockNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'sent_at',
    ];

    public function product()
    {
        return $this->morphedByMany(Product::class,
        'notifiable',
            'back_to_stock_notifiables',
            'id',
            'notification_id');
    }

    public function collection()
    {
        return $this->morphedByMany(Collection::class,
         'notifiable',
            'back_to_stock_notifiables',
            'id',
            'notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}