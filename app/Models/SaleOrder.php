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
        'tanggal',
        'no_order',
        'total_harga',
        'biaya_angkutan',
        'tunai',
        'pajak',
        'isactive',
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
}
