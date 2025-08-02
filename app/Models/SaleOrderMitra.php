<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderMitra extends Model
{
    protected $guarded = [];
    protected $table = 'sale_order_mitras';

    protected $fillable = [
        'sale_order_id',
        'branch_id',
        'pegawai_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
        'harga_satuan',
        'keterangan',
        'pajak',
        'approved',
        'approved_by',
        'created_by',
        'updated_by',
    ];

    public function sale_order()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
