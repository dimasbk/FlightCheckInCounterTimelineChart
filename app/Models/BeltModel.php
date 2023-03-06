<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltModel extends Model
{
    protected $table = "tb_belt";

    protected $fillable = [
        'belt'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id';
}