<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class AttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'attribute_id',
        'value_text',
        'value_numeric',
        'value_boolean',
        'org_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hotel) {
            $hotel->id = Uuid::uuid4()->toString();
        });
    }

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment

    public function categories()
    {
        return $this->belongsToMany(CatalogueRoom::class, 'catalogue_room_attribute', 'attribute_value_id', 'catalogue_room_id');
    }


}
