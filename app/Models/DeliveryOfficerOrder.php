<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOfficerOrder extends Model
{
    protected $guarded = [];
    protected $table = 'delivery_officer_orders';

    protected $fillable = [
        'delivery_officer_id',
        'delivery_order_id',
        'sale_order_id',
    ];

    public function delivery_officer()
    {
        return $this->belongsTo(DeliveryOfficer::class);
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function sales_order()
    {
        return $this->belongsTo(SaleOrder::class);
    }
}
