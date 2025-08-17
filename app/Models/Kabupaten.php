<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $guarded = [];
    protected $table = 'kabupatens';

    protected $fillable = [
        'propinsi_id',
        'nama',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function propinsi()
    {
        return $this->belongsTo(Propinsi::class);
    }
}
