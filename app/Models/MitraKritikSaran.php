<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraKritikSaran extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_kritik_sarans';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jenis',
        'judul',
        'keterangan',
        'isactive',
    ];
}
