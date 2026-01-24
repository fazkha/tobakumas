<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraKasbon extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_kasbons';

    protected $fillable = [
        'user_id',
        'minggu',
        'plafon',
        'sisa_plafon',
        'isactive',
    ];
}
