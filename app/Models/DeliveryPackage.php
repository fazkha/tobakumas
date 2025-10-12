<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPackage extends Model
{
    protected $guarded = [];
    protected $table = 'delivery_packages';

    protected $fillable = [
        'delivery_officer_id',
        'barang_id',
        'satuan_id',
        'harga_satuan',
        'kuantiti',
        'created_by',
        'updated_by',
    ];

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

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    public function order()
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id');
    }
}
