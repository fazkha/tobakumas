<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosBiayaGrup extends Model
{
    protected $guarded = [];
    protected $table = 'pos_biaya_grups';

    protected $fillable = [
        'kode',
        'nama',
        'isactive',
    ];
}
