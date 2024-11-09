<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($region) {
            $region->id = Uuid::uuid4()->toString();
        });

        static::deleting(function ($region) {
                $region->cities()->each(function ($city) {
                    $city->delete();
                });
        });

        static::forceDeleting(function ($region) {
            $citiesTrashed = $region->cities()->onlyTrashed()->get();
            $citiesTrashed->each(function ($city) {
                $city->forceDelete();
            });
        });

        static::restoring(function ($region) {
            $citiesTrashed = $region->cities()->onlyTrashed()->get();
            $citiesTrashed->each(function ($city) {
                $city->restore();
            });
        });
    }

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];
}
