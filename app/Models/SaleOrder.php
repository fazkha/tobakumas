<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'sale_orders';

    protected $fillable = [
        'branch_id',
        'customer_id',
        'hke',
        'tanggal',
        'no_order',
        'total_harga',
        'biaya_angkutan',
        'tunai',
        'jatuhtempo',
        'pajak',
        'isactive',
        'isready',
        'isready_by',
        'isready_at',
        'ispackaged',
        'ispackaged_by',
        'ispackaged_at',
        'approved',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale_order_detail()
    {
        return $this->hasMany(SaleOrderDetail::class);
    }

    public function prod_order()
    {
        return $this->hasOne(ProdOrder::class, 'sale_order_id');
    }
}
