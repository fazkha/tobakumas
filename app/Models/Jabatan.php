<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $guarded = [];
    protected $table = 'jabatans';

    protected $fillable = [
        'nama',
        'keterangan',
        'islevel',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
