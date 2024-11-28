<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $table = 'comments';

    protected $fillable = [
        'rate_id',
        'user_id',
        'content'
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
