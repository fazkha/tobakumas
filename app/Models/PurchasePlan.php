<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasePlan extends Model
{
    protected $guarded = [];
    protected $table = 'purchase_plans';

    protected $fillable = [
        'branch_id',
        'supplier_id',
        'periode_tahun',
        'periode_bulan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
