<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraOmzetPengeluaranDetail extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_op_details';

    protected $fillable = [
        'mitra_omzet_pengeluaran_id',
        'jenis_pengeluaran_mitra_id',
        'harga',
        'image_lokasi',
        'image_nama',
        'image_type',
        'approved',
    ];

    public function omzet()
    {
        return $this->belongsTo(MitraOmzetPengeluaran::class, 'mitra_omzet_pengeluaran_id');
    }

    public function jenis_pengeluaran_mitra()
    {
        return $this->belongsTo(JenisPengeluaranMitra::class, 'jenis_pengeluaran_mitra_id');
    }
}
