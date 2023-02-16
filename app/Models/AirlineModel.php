<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineModel extends Model
{
    protected $table = "tb_airline";

    protected $fillable = [
        'airline, chartColor'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id_airline';
}
