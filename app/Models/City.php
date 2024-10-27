<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'region_id',
    ];

    public function region(){
        return $this->belongsTo(Region::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($city) {
            $city->id = Uuid::uuid4()->toString();
        });
    }

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'region_id' => 'string'
    ];
}
