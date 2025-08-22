<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brandivjabkab extends Model
{
    protected $guarded = [];
    protected $table = 'brandivjabkabs';

    protected $fillable = [
        'brandivjab_id',
        'propinsi_id',
        'kabupaten_id',
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
}
