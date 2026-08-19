<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tmp extends Model
{
    protected $guarded = [];
    protected $table = 'tmp';
    public $timestamps = false;

    protected $fillable = [
        'parm',
        'value',
    ];
}
