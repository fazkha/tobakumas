<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuteGerobak extends Model
{
    protected $guarded = [];
    protected $table = 'rute_gerobaks';

    protected $fillable = [
        'user_id',
        'status',
        'latitude',
        'longitude',
        'isactive',
        'timesaved',
    ];
}
