<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraPermintaanIzin extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_permintaan_izins';

    protected $fillable = [
        'mitra_id',
        'jenis_izin_pegawai_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'approved_hrd',
        'created_by',
        'updated_by',
    ];
}
