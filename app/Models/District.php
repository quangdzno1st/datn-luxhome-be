<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class District extends Model

{

    use HasFactory;



    public $timestamps = FALSE;



    public function province(){

        return $this->belongsTo(Province::class);

    }

}

