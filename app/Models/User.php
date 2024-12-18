<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

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
        'group_id',
        'total_amount_ordered',
        'password',
        'cccd',
        'is_active',
        'rank',
        'type',
        'avatar'
    ];

    const CUSTOMER = 1;
    const ADMIN = 2;
    const HOTELIER = 3;
    const STAFF = 4;

    public const ROLES = [
        self::CUSTOMER => 'Người dùng',
        self::ADMIN => 'Chủ chuỗi KS',
        self::HOTELIER => 'Quản lý KS',
        self::STAFF => 'Nhân viên KS',
    ];
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

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }


    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'wallets', 'user_id', 'voucher_id');
//            ->withPivot('created_at', 'updated_at')
            // ->where('end_date', '>', now());
    }

}
