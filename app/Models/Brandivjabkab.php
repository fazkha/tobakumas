<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brandivjabkab extends Model
{
    protected $guarded = [];
    protected $table = 'brandivjabkabs';

    protected $fillable = [
        'brandivjab_id',
        'propinsi_id',
        'kabupaten_id',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
