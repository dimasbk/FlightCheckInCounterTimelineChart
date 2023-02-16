<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirportCodeModel extends Model
{
    protected $table = "tb_airport";

    protected $fillable = [
        'airport_code'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id_airport';
}
