<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraPengumuman extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_pengumumans';

    protected $fillable = [
        'tanggal',
        'judul',
        'keterangan',
        'lokasi',
        'gambar',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
