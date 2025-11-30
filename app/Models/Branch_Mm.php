<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch_Mm extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'branches';
    protected $connection = 'mm_db';

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
        'latitude',
        'longitude',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function propinsi()
    {
        return $this->belongsTo(Propinsi_Mm::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten_Mm::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan_Mm::class);
    }
}
