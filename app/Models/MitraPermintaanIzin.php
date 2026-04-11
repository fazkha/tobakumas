<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraPermintaanIzin extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_permintaan_izins';

    protected $fillable = [
        'branch_id',
        'mitra_id',
        'jenis_izin_pegawai_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'approved_hrd',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisIzinPegawai::class, 'jenis_izin_pegawai_id');
    }
}
