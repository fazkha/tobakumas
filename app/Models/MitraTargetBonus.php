<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraTargetBonus extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_target_bonuses';

    protected $fillable = [
        'target',
        'bonus',
        'isactive',
    ];
}
