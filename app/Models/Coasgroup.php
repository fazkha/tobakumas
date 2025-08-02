<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coasgroup extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'coasgroups';

    protected $fillable = [
        'code',
        'name',
        'description',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function coa()
    {
        return $this->hasMany(Coa::class);
    }
}
