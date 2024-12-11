<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    const RATE = [
        '1' => ['Rất Kém', '#FF0000'],
        '2' => ['Kém', '#FF9800'],
        '3' => ['Bình Thường', '#FBC02D'],
        '4' => ['Hài Lòng', '#4CAF50'],
        '5' => ['Rất Hài Lòng', '#2E7D32'],
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'hotel_id',
        'rate',
        'content',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    // Quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    //Quan hệ với bảng hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }
    public function comment()
    {
        return $this->hasOne(Comment::class, 'rate_id', 'id');
    }
}
