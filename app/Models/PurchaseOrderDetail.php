<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    protected $guarded = [];
    protected $table = 'purchase_order_details';

    protected $fillable = [
        'purchase_order_id',
        'branch_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
        'harga_satuan',
        'keterangan',
        'discount',
        'pajak',
        'isaccepted',
        'isreturned',
        'satuan_terima_id',
        'kuantiti_terima',
        'keterangan_terima',
        'approved',
        'approved_by',
        'created_by',
        'updated_by',
    ];

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
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
