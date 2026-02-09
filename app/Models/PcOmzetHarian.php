<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcOmzetHarian extends Model
{
    protected $guarded = [];
    protected $table = 'pc_omzet_harians';

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'omzet',
        'sisa_adonan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
