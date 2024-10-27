<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Room extends Model
{
    protected $fillable = [
        'id',
        'org_id',
        'code',
        'catalogue_room_id',
        'status'
    ];

    // auto render uuid
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($hotel) {
            $hotel->id = Uuid::uuid4()->toString();
        });
    }

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment
}