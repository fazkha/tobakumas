<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'purchase_orders';

    protected $fillable = [
        'branch_id',
        'supplier_id',
        'tanggal',
        'no_order',
        'total_harga',
        'biaya_angkutan',
        'tunai',
        'pajak',
        'isactive',
        'approved',
        'approved_by',
        'created_by',
        'updated_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase_order_detail()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }
}
