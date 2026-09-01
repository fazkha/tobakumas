<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheet extends Model
{
    protected $guarded = [];
    protected $table = 'google_sheets';

    protected $fillable = [
        'tahun',
        'bulan',
        'sheet_id',
        'isactive',
    ];
}
