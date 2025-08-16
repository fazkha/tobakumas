<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanPegawai extends Model
{
    protected $guarded = [];
    protected $table = 'jabatan_pegawais';

    protected $fillable = [
        'branch_id',
        'division_id',
        'pegawai_id',
        'jabatan_id',
        'tanggal_mulai',
        'tanggal_akhir',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
