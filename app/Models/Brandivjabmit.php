<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brandivjabmit extends Model
{
    protected $guarded = [];
    protected $table = 'brandivjabmits';

    protected $fillable = [
        'brandivjab_id',
        'mitra_id',
        'gerobak_id',
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

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
