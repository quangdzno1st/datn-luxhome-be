<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Ward extends Model

{

    use HasFactory;



    protected $table = 'ward';



    public $timestamps = FALSE;



    public function district(){

        return $this->belongsTo(District::class);

    }

}

