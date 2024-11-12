<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'voucher_id',
        'booking_fee',
        'phone',
        'email',
        'name',
        'code',
        'qr_code',
        'status',
        'start_date',
        'end_date',
        'check_in',
        'check_out',
        'note',
        'incidental_costs',
        'total_amount',
        'transaction_id'
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

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'catalogue_room_attribute', 'catalogue_room_id', 'attribute_value_id');
    }
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function bookingService() {
        return $this->hasMany(BookingService::class, 'order_id');
    }
}
