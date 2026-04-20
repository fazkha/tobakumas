<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPendapatan extends Model
{
    protected $guarded = [];
    protected $table = 'pos_pendapatan_grups';

    protected $fillable = [
        'grup_id',
        'kode',
        'nama',
        'isactive',
    ];
}
