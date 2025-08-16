<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $guarded = [];
    protected $table = 'divisions';

    protected $fillable = [
        'nama',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
