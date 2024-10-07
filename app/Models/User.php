<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'id',
        'phone',
        'email',
        'address',
        'org_id',
        'password',
        'cccd',
        'is_active',
        'rank_id',
        'type',
    ];

    const CUSTOMER = 1;
    const ACTIVE = 1;
    const INACTIVE = 2;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        //'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        //'profile_photo_url',
    ];


    function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    function operation()
    {
        return $this->hasOne(SaleOperation::class, 'user_id');
    }
}
