<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'branches';

    protected $fillable = [
        'propinsi_id',
        'kabupaten_id',
        'kode',
        'nama',
        'alamat',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
