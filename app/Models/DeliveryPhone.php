<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'phone',
        'default',
    ];

    // One to many relationship (Inverse)  Delivery Company --> Phones
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
