<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeDetail extends Model
{
    protected $guarded = [];
    protected $table = 'recipe_details';

    protected $fillable = [
        'recipe_id',
        'urutan',
        'tahapan',
        'keterangan',
        'created_by',
        'updated_by',
    ];
}
