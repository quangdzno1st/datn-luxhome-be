<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class CatalogueRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hotel_id',
        'price',
        'status',
        'description',
        'thumbnail',
        'number_adult',
        'number_child',
        'price_hour',
        'acreage'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'catalogue_room_attribute', 'catalogue_room_id', 'attribute_id');
    }
    public function rooms()
    {
        return $this->hasMany(Room::class, 'catalogue_room_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'object_id', 'id');
    }
}
