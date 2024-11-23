<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenities extends Model
{
    use HasFactory, HasUuids, softDeletes;

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
