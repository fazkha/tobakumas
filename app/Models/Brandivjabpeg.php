<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brandivjabpeg extends Model
{
    protected $guarded = [];
    protected $table = 'brandivjabpegs';

    protected $fillable = [
        'brandivjab_id',
        'pegawai_id',
        'tanggal_mulai',
        'tanggal_akhir',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function brandivjab()
    {
        return $this->belongsTo(Brandivjab::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
