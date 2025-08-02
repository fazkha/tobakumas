<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'satuans';

    protected $fillable = [
        'singkatan',
        'nama_lengkap',
        'keterangan',
        'isactive',
    ];
}
