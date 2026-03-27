<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPc extends Model
{
    protected $guarded = [];
    protected $table = 'order_pcs';

    protected $fillable = [
        'pc_id',
        'barang_id',
        'satuan_id',
        'hke',
        'qty',
        'keterangan',
        'created_by',
        'updated_by',
    ];
}
