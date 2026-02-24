<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPengeluaranCabang extends Model
{
    protected $guarded = [];
    protected $table = 'jenis_pengeluaran_cabangs';

    protected $fillable = [
        'nama',
        'isactive',
    ];
}
