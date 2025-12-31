<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraOmzetPengeluaranDetail extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_op_details';

    protected $fillable = [
        'mitra_omzet_pengeluaran_id',
        'keterangan',
        'harga',
    ];

    public function omzet()
    {
        return $this->belongsTo(MitraOmzetPengeluaran::class, 'mitra_omzet_pengeluaran_id');
    }
}
