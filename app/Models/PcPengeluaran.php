<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcPengeluaran extends Model
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
