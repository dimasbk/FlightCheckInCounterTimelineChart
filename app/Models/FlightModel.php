<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightModel extends Model
{
    protected $table = "tb_schedule";
    protected $fillable = [
        'flight_number',
        'id_destination',
        'airplane_type',
        'id_airline',
        'schedule_time',
        'id_checkin_desk',
        'gate',
        'pax',
        'cic',
        'flightType'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id_schedule';
}