<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraOmzetPengeluaran extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_omzet_pengeluarans';

    protected $fillable = [
        'user_id',
        'tanggal',
        'omzet',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(MitraOmzetPengeluaranDetail::class, 'mitra_omzet_pengeluaran_id');
    }
}
