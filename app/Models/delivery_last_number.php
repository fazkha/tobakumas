<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class delivery_last_number extends Model
{
    protected $guarded = [];
    protected $table = 'delivery_last_number';

    protected $fillable = [
        'branch_id',
        'pegawai_id',
        'tahun',
        'bulan',
        'last_number',
    ];
}
