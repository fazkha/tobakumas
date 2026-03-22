<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisIzinPegawai extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'jenis_izin_pegawais';

    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'isactive',
    ];
}
