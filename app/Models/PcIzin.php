<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcIzin extends Model
{
    protected $guarded = [];
    protected $table = 'pc_permintaan_izins';

    protected $fillable = [
        'branch_id',
        'pegawai_id',
        'jenis_izin_pegawai_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'approved_hrd',
        'created_by',
        'updated_by',
    ];

    public function getTanggalMulaiAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->timezone('Asia/Jakarta');
    }

    public function getTanggalSelesaiAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->timezone('Asia/Jakarta');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisIzinPegawai::class, 'jenis_izin_pegawai_id');
    }
}
