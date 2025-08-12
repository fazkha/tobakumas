<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderDetail extends Model
{
    protected $guarded = [];
    protected $table = 'delivery_order_details';

    protected $fillable = [
        'branch_id',
        'delivery_order_id',
        'sale_order_id',
        'sale_order_detail_id',
        'paket_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    public function order()
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id');
    }

    public function order_detail()
    {
        return $this->belongsTo(SaleOrderDetail::class, 'sale_order_detail_id');
    }

    public function view_order_detail()
    {
        return $this->belongsTo(ViewSaleOrderDetail::class, 'sale_order_detail_id', 'id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
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
