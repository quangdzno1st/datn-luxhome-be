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
        'image',
        'view',
        'like',
        'org_id'
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

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'catalogue_room_attribute', 'catalogue_room_id', 'attribute_value_id');
    }
    public function rooms()
    {
        return $this->hasMany(Room::class, 'catalogue_room_id');
    }
}
