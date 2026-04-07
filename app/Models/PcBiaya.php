<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcBiaya extends Model
{
    protected $guarded = [];
    protected $table = 'pc_pengeluarans';

    protected $fillable = [
        'branch_id',
        'user_id',
        'tanggal',
        'jenis_pengeluaran_cabang_id',
        'harga',
        'image_lokasi',
        'image_nama',
        'image_type',
        'approved',
        'approved_fin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisPengeluaranCabang::class, 'jenis_pengeluaran_cabang_id');
    }
}
