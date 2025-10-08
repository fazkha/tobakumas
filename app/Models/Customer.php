<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'customers';

    protected $fillable = [
        'branch_id',
        'customer_group_id',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
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

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class);
    }
}
