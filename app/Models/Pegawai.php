<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $guarded = [];
    protected $table = 'pegawais';

    protected $fillable = [
        'nama_lengkap',
        'alamat_tinggal',
        'telpon',
        'kelamin',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function jabatan_pegawai()
    {
        return $this->hasMany(JabatanPegawai::class);
    }
}
