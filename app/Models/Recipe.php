<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $guarded = [];
    protected $table = 'recipes';

    protected $fillable = [
        'judul',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
