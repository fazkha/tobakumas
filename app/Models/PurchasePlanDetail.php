<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasePlanDetail extends Model
{
    protected $guarded = [];
    protected $table = 'purchase_plan_details';

    protected $fillable = [
        'branch_id',
        'purchase_plan_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
        'sisa_kuota',
    ];

    public function purchase_plan()
    {
        return $this->belongsTo(PurchasePlan::class);
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
