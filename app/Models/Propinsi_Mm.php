<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propinsi_Mm extends Model
{
    protected $guarded = [];
    protected $table = 'propinsis';
    protected $connection = 'mm_db';

    protected $fillable = [
        'nama',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
