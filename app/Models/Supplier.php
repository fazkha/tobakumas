<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'suppliers';

    protected $fillable = [
        'branch_id',
        'kode',
        'nama',
        'alamat',
        'tanggal_gabung',
        'kontak_nama',
        'kontak_telpon',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
