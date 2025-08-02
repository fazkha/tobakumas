<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjenisBarang extends Model
{
    protected $guarded = [];
    protected $table = 'subjenis_barangs';

    protected $fillable = [
        'jenis_barang_id',
        'nama',
        'keterangan',
        'isactive',
    ];
}
