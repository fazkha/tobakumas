<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan_Mm extends Model
{
    protected $guarded = [];
    protected $table = 'kecamatans';
    protected $connection = 'mm_db';

    protected $fillable = [
        'propinsi_id',
        'kabupaten_id',
        'nama',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function propinsi()
    {
        return $this->belongsTo(Propinsi_Mm::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten_Mm::class);
    }
}
