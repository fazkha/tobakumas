<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salelastnumber extends Model
{
    protected $guarded = [];
    protected $table = 'sale_last_number';

    protected $fillable = [
        'branch_id',
        'customer_id',
        'tahun',
        'bulan',
        'last_number',
    ];
}
