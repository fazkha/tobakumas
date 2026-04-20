<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahOperasi extends Model
{
    protected $guarded = [];
    protected $table = 'wilayah_operasis';

    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'isactive',
    ];
}
