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
        'email',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function view_pegawai_jabatan()
    {
        return $this->belongsTo(ViewPegawaiJabatan::class, 'id', 'pegawai_id');
    }
}
