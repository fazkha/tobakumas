<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdOrderDetail extends Model
{
    protected $guarded = [];
    protected $table = 'prod_order_details';

    protected $fillable = [
        'prod_order_id',
        'branch_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
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

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
