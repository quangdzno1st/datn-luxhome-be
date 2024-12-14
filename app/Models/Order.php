<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    const RATING_STATUS = [
        '1' => 'Đã đánh giá',
        '2' => 'Chưa đánh giá'
    ];

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
        'net_amount',
        'transaction_id',
        'is_requried_cancel',
        'is_rating'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function attributeValues()
    {
        return $this->belongsToMany(Attribute::class, 'catalogue_room_attribute', 'catalogue_room_id', 'attribute_value_id');
    }
//    public function orderItem()
//    {
//        return $this->hasMany(OrderItem::class, 'order_id');
//    }
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function bookingService() {
        return $this->hasMany(BookingService::class, 'order_id');
    }
}
