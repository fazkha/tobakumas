<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resign extends Model
{
    protected $guarded = [];
    protected $table = 'resigns';

    protected $fillable = [
        'user_id',
        'tanggal',
        'keterangan',
        'tanggapan_pc',
        'tanggapan_hrd',
        'approved_hrd',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
