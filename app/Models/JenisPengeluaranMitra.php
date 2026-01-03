<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPengeluaranMitra extends Model
{
    protected $guarded = [];
    protected $table = 'jenis_pengeluaran_mitras';

    protected $fillable = [
        'nama',
        'isactive',
    ];
}
