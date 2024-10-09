<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Ramsey\Uuid\Uuid;
class Voucher extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'id',
        'code',
        'description',
        'status',
        'quantity',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'min_price',
        'max_price',
        'rank_id',
        'conditional_rank',
        'conditional_total_amount'
    ];
    // public $incrementing = false;   // Không tự động tăng ID
    // protected $keyType = 'string';  // ID sẽ là kiểu string, không phải integer

    // Sử dụng sự kiện "creating" để gán UUID khi tạo mới bản ghi
    // protected static function boot()
    // {
    //     parent::boot();

    //     // Tạo UUID tự động khi tạo bản ghi
    //     static::creating(function ($model) {
    //         if (!$model->getKey()) {
    //             $model->id = (string) Uuid::uuid4();  // Tạo UUID cho trường 'id'
    //         }
    //     });
    // }
}