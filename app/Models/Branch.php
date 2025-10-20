<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'branches';

    protected $fillable = [
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kode',
        'nama',
        'alamat',
        'kodepos',
        'keterangan',
        'email',
        'isactive',
        'created_by',
        'updated_by',
    ];

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
