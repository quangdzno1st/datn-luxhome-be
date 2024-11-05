<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'image';

    protected $fillable = [
        'id',
        'path',
        'alt',
        'object_id',
    ];

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment

    protected $casts = [
        'id' => 'string',          // Khóa chính UUID
        'object_id' => 'string', // Khóa ngoại UUID
    ];
}
