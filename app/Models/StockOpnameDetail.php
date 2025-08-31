<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $guarded = [];
    protected $table = 'stock_opname_details';

    protected $fillable = [
        'branch_id',
        'stock_opname_id',
        'barang_id',
        'satuan_id',
        'stock',
        'minstock',
        'before_stock',
        'before_minstock',
        'before_satuan_id',
        'keterangan',
        'adjust_stock',
        'adjust_satuan_id',
        'adjust_harga',
        'adjust_by',
        'adjust_at',
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

    public function adjust_satuan()
    {
        return $this->belongsTo(Satuan::class, 'adjust_satuan_id');
    }
}
