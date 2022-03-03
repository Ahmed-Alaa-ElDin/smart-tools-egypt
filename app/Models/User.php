<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;



class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use HasTranslations;
    use SoftDeletes;


    public $translatable = ['f_name', 'l_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'f_name',
        'l_name',
        'email',
        'password',
        'gender',
        'visit_num',
        'last_visit_at',
        'profile_photo_path',
        'email_verified_at',
        'birth_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_visit_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = [
    // ];

    // One to many relationship  User --> Addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // One to many relationship  User --> phones
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

}
