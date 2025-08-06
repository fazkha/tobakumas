<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderDetail extends Model
{
    protected $guarded = [];
    protected $table = 'sale_order_details';

    protected $fillable = [
        'sale_order_id',
        'branch_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
        'stock',
        'harga_satuan',
        'keterangan',
        'pajak',
        'ispackaged',
        'ispackaged_by',
        'ispackaged_at',
        'approved',
        'approved_by',
        'approved_at',
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

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
