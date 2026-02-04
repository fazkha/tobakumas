<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraPengumumanUntuk extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_pengumuman_untuks';

    protected $fillable = [
        'mitra_pengumuman_id',
        'jabatan_id',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
