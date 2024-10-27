<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Room extends Model
{
    use HasFactory;

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
}