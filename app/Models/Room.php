<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'hotel_id',
        'code',
        'catalogue_room_id',
        'status'
    ];
    protected $casts = [
        'status' => 'integer',  // Tự động chuyển đổi status từ string (nếu có) sang integer
    ];
    // auto render uuid
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function categories()
    {

        return $this->belongsTo(CatalogueRoom::class, 'catalogue_room_id');
    }

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment

    const STATUS_REPAIRING = 0;
    const STATUS_CLEANING = 1;
    const STATUS_NOT_IN_USE = 2;
    const STATUS_AVAILABLE = 3;

    public function getStatusLabel()
    {
        $statuses = [
            self::STATUS_REPAIRING => 'Đang sửa chữa',
            self::STATUS_CLEANING => 'Đang dọn dẹp',
            self::STATUS_NOT_IN_USE => 'Không còn sử dụng',
            self::STATUS_AVAILABLE => 'Sẵn sàng sử dụng',
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }
}

