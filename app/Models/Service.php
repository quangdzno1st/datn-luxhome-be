<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, HasUuids, softDeletes;

    const TYPE_SERVICE = [
        '1' => 'Dịch vụ mất phí',
        '2' => 'Dịch vụ đi kèm'
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'type',
        'hotel_id',
        'status'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function hotel()
    {
        return $this->hasOne(Hotel::class, 'id', 'hotel_id');
    }

}
