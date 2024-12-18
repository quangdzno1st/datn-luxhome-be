<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'location',
        'quantity_of_room',
        'star',
        'city_id',
        'phone',
        'email',
        'status',
        'quantity_floor',
        'thumbnail',
        'description',
        'province',
        'district',
        'commune',
        'latitude',
        'longitude',
        'view',
        'percent_incidental'
    ];

    // auto render uuid
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hotel) {
            $hotel->id = Uuid::uuid4()->toString();
        });
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'org_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'object_id');
    }
    public function catalogues()
    {
        return $this->hasMany(CatalogueRoom::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class, 'hotel_id', 'id');
    }

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment

    protected $casts = [
        'id' => 'string',          // Khóa chính UUID
        'city_id' => 'string', // Khóa ngoại UUID
        'status' => 'boolean',
    ];

}
