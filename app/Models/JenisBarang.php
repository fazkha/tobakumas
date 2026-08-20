<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'jenis_barangs';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'lpp_nama',
        'keterangan',
        'isactive',
    ];
}
