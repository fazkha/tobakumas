<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'coas';

    protected $fillable = [
        'coasgroups_id',
        'code',
        'name',
        'description',
        'balance',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function coasgroups()
    {
        return $this->belongsTo(Coasgroup::class);
    }
}
