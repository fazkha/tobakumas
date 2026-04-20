<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderLibur extends Model
{
    protected $guarded = [];
    protected $table = 'kalender_liburs';

    protected $fillable = [
        'tanggal',
        'keterangan',
    ];
}
