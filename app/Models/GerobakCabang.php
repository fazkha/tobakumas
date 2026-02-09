<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GerobakCabang extends Model
{
    protected $guarded = [];
    protected $table = 'gerobak_cabangs';

    protected $fillable = [
        'branch_id',
        'gerobak_id',
        'mitra_id',
    ];
}
