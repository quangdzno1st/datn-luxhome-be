<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = "order_items";
    protected $fillable = [
        'name',
        'hotel_id',
        'price',
        'status',
        'description',
        'image',
        'view',
        'like',
        'org_id'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'catalogue_room_id');
    }
}
