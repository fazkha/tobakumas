<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $guarded = [];
    protected $table = 'pakets';

    protected $fillable = [
        'nama',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
