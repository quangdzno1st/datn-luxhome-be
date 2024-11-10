<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, HasUuids, softDeletes;

    const TYPE_SERVICE = [
        '1' => 'Ngoài khách sạn',
        '2' => 'Trong khách sạn'
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'type'
    ];

    protected $casts = [
        'id' => 'string',
    ];

}
