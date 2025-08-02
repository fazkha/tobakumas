<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchaselastnumber extends Model
{
    protected $guarded = [];
    protected $table = 'purchase_last_number';

    protected $fillable = [
        'branch_id',
        'supplier_id',
        'tahun',
        'bulan',
        'last_number',
    ];
}
