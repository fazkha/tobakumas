<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $guarded = [];
    protected $table = 'pegawais';

    protected $fillable = [
        'nama_lengkap',
        'nama_panggilan',
        'nik',
        'nip',
        'alamat_asal',
        'alamat_tinggal',
        'telpon',
        'kelamin',
        'tempat_lahir',
        'tanggal_lahir',
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
