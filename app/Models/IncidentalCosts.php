<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentalCosts extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'description',
        'price',
        'order_id',
        'room_id'
    ];
}
