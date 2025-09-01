<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brandivjabkec extends Model
{
    protected $guarded = [];
    protected $table = 'brandivjabkecs';

    protected $fillable = [
        'brandivjab_id',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function brandivjab()
    {
        return $this->belongsTo(Brandivjab::class);
    }

    public function propinsi()
    {
        return $this->belongsTo(Propinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
