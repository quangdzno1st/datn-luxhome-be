<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'title',
    ];


//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($city) {
//            $city->id = Uuid::uuid4()->toString();
//        });
//    }

//    protected $keyType = 'string';
//    public $incrementing = false;
//
//    protected $casts = [
//        'id' => 'string',
//        'region_id' => 'string'
//    ];
}
