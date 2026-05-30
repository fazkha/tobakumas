<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcTargetBonus extends Model
{
    protected $guarded = [];
    protected $table = 'pc_target_bonuses';

    protected $fillable = [
        'tipegaji',
        'hpp',
        'r2omzet',
        'omzet',
        'bonus',
        'isactive',
    ];
}
