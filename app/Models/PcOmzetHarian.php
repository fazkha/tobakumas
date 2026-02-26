<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcOmzetHarian extends Model
{
    protected $guarded = [];
    protected $table = 'pc_omzet_harians';

    protected $fillable = [
        'branch_id',
        'pegawai_id',
        'tanggal',
        'omzet',
        'sisa_adonan',
        't_omzet',
        't_adonan',
        'image_lokasi',
        'image_nama',
        'image_type',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
