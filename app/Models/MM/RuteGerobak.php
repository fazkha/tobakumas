<?php

namespace App\Models\mm;

use Illuminate\Database\Eloquent\Model;

class RuteGerobak extends Model
{
    protected $guarded = [];
    protected $table = 'rute_gerobaks';

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'isactive',
    ];
}
