<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketDetail extends Model
{
    protected $guarded = [];
    protected $table = 'paket_details';

    protected $fillable = [
        'paket_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
        'created_by',
        'updated_by',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
