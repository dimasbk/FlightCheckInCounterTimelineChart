<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinDeskModel extends Model
{
    protected $table = "tb_checkin_desk";

    protected $fillable = [
        'checkin_desk'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id';
}
