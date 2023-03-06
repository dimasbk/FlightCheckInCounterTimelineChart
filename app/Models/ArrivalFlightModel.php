<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrivalFlightModel extends Model
{
    protected $table = "tb_arrival";
    protected $fillable = [
        'flight_number',
        'id_origin',
        'airplane_type',
        'id_airline',
        'schedule_time',
        'belt',
        'flightType'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id_arrival';
}