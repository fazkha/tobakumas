<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcKasbon extends Model
{
    protected $guarded = [];
    protected $table = 'pc_kasbons';

    protected $fillable = [
        'user_id',
        'minggu',
        'plafon',
        'sisa_plafon',
        'isactive',
    ];
}
