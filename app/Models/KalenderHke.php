<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderHke extends Model
{
    protected $guarded = [];
    protected $table = 'kalender_hkes';

    protected $fillable = [
        'tanggal',
        'hari',
        'hke'
    ];
}
