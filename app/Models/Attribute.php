<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'content',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hotel) {
            $hotel->id = Uuid::uuid4()->toString();
        });
    }

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment

}
