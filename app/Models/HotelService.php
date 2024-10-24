<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelService extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'hotel_service';

    protected $fillable = [
        'hotel_id',
        'service_id',
    ];

    protected $casts = [
        'id' => 'string',
        'hotel_id' => 'string',
        'service_id' => 'string',
    ];
}
