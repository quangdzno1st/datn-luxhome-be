<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    CONST OPEN = 'open';
    CONST CLOSE = 'close';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'location',
        'quantity_of_room',
        'star',
        'city_id',
        'phone',
        'email',
        'status',
        'quantity_floor'
    ];

    // auto render uuid
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hotel) {
            $hotel->id = Uuid::uuid4()->toString();
        });
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function services(){
        return $this->belongsToMany(Service::class);
    }

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment

    protected $casts = [
        'id' => 'string',          // Khóa chính UUID
        'city_id' => 'string', // Khóa ngoại UUID
    ];

}
