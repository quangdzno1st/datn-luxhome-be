<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonKeyCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'current_index',
        'object_type',
    ];
}
