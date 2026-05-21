<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraUbahHari extends Model
{
    protected $guarded = [];
    protected $table = 'mitra_ubah_haris';

    protected $fillable = [
        'branch_id',
        'user_id',
        'jenis_ubah',
        'tanggal',
        'keterangan',
        'approved_hrd',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
