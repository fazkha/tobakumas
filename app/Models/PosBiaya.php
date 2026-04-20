<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosBiaya extends Model
{
    protected $guarded = [];
    protected $table = 'pos_biayas';

    protected $fillable = [
        'grup_id',
        'kode',
        'nama',
        'isactive',
    ];
}
