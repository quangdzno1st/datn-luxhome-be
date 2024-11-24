<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogueRoomAttribute extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'catalogue_room_attribute';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'catalogue_room_id',
        'attribute_id',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
