<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
<<<<<<< HEAD
        'org_id',
        'code',
        'catalogue_room_id',
        'status'
=======
        'catalogue_room_id',
        'status',
        'org_id'
>>>>>>> 11e2e04 (viết api số lượng phòng còn lại theo điều kiện lọc của từng loại phòng(mặc định là ngày hiện tại và ngày hôm sau) , Viết api lấy danh sách phòng còn trống theo điều kiện lọc(mặc định là ngày hiện tại và ngày hôm sau), Viết api tìm kiếm loại phòng còn phòng trống theo điều kiện lọc(Mặc định tìm theo ngày hiện tại và ngày tiếp theo))
    ];

    // auto render uuid
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function categories()
    {
<<<<<<< HEAD
        return $this->belongsToMany(AttributeValue::class, 'catalogue_room_attribute', 'catalogue_room_id', 'attribute_value_id');

=======
        return $this->belongsTo(CatalogueRoom::class, 'catalogue_room_id');
>>>>>>> 11e2e04 (viết api số lượng phòng còn lại theo điều kiện lọc của từng loại phòng(mặc định là ngày hiện tại và ngày hôm sau) , Viết api lấy danh sách phòng còn trống theo điều kiện lọc(mặc định là ngày hiện tại và ngày hôm sau), Viết api tìm kiếm loại phòng còn phòng trống theo điều kiện lọc(Mặc định tìm theo ngày hiện tại và ngày tiếp theo))
    }

    protected $keyType = 'string';  // Khóa chính là kiểu chuỗi
    public $incrementing = false;   // Tắt auto-increment
}