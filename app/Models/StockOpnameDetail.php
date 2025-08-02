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
