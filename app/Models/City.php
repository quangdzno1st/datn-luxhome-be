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
        'thumbnail'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($city) {
            $city->id = Uuid::uuid4()->toString();
        });

        static::deleting(function ($city) {
            $city->hotels()->each(function ($hotel) {
                $hotel->delete();
            });
        });

        static::forceDeleting(function ($city) {
            $hotelsTrashed = $city->hotels()->onlyTrashed()->get();
            $hotelsTrashed->each(function ($hotel) {
                $hotel->forceDelete();
            });
        });

        static::restoring(function ($city) {
            $hotelsTrashed = $city->hotels()->onlyTrashed()->get();
            $hotelsTrashed->each(function ($hotel) {
                $hotel->restore();
            });
        });
    }

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'region_id' => 'string'
    ];
}
