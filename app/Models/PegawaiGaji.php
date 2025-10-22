<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiGaji extends Model
{
    protected $guarded = [];
    protected $table = 'pegawai_gajis';

    protected $fillable = [
        'pegawai_id',
        'gaji_pokok',
        't1_keterangan',
        't1_gaji',
        't2_keterangan',
        't2_gaji',
        't3_keterangan',
        't3_gaji',
        'rek_nama_bank',
        'rek_nomor',
        'rek_nama_pemilik',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
