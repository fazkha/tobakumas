<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPendapatanGrup extends Model
{
    protected $guarded = [];
    protected $table = 'pos_pendapatan_grups';

    protected $fillable = [
        'kode',
        'nama',
        'isactive',
    ];
}
