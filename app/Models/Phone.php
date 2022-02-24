<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'master',
    ];

        // One to many relationship (Reverse)  User --> Phones
        public function user()
        {
            return $this->belongsTo(User::class);
        }

}
