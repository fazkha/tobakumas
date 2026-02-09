<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $guarded = [];
    protected $table = 'mitras';

    protected $fillable = [
        'nama_lengkap',
        'nama_panggilan',
        'nik',
        'alamat_tinggal',
        'telpon',
        'kelamin',
        'keterangan',
        'email',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
