<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdOrder extends Model
{
    protected $guarded = [];
    protected $table = 'prod_orders';

    protected $fillable = [
        'sale_order_id',
        'branch_id',
        'tanggal',
        'petugas_1_id',
        'petugas_2_id',
        'tanggungjawab_id',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function order()
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id');
    }

    public function petugas_1()
    {
        return $this->belongsTo(Pegawai::class, 'petugas_1_id');
    }

    public function petugas_2()
    {
        return $this->belongsTo(Pegawai::class, 'petugas_2_id');
    }

    public function tanggungjawab()
    {
        return $this->belongsTo(Pegawai::class, 'tanggungjawab_id');
    }
}
