<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraAverageOmzet extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_average_omzets';

    protected $fillable = [
        'user_id',
        'minggu',
        'rata2',
        'trend',
        'pct',
    ];
}
