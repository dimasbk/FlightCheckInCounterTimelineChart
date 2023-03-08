<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GateModel extends Model
{
    protected $table = "tb_gate";

    protected $fillable = [
        'gate'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id';
}