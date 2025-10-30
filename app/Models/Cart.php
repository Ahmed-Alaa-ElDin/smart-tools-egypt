<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'shoppingcart';
    protected $primaryKey = 'identifier';
    protected $fillable = [
        'identifier',
        'instance',
        'content',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'identifier', 'id');
    }

    /**
     * Get the cart content.
     */
    public function getContentAttribute($value)
    {
        return unserialize($value);
    }


    /**
     * Get carts only.
     */
    public function scopeCartsOnly($query)
    {
        return $query->where('instance', 'cart');
    }

    /**
     * Get not empty carts.
     */
    public function scopeNotEmpty($query)
    {
        return $query->where('content', '!=', 'O:29:"Illuminate\Support\Collection":2:{s:8:"*items";a:0:{}s:28:"*escapeWhenCastingToString";b:0;}');
    }
}
