<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'barangs';

    protected $fillable = [
        'branch_id',
        'gudang_id',
        'satuan_beli_id',
        'satuan_jual_id',
        'satuan_stock_id',
        'jenis_barang_id',
        'subjenis_barang_id',
        'nama',
        'merk',
        'keterangan',
        'harga_satuan',
        'harga_satuan_jual',
        'stock',
        'minstock',
        'lokasi',
        'gambar',
        'gambar_nama_awal',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function satuan_beli()
    {
        return $this->belongsTo(Satuan::class, 'satuan_beli_id');
    }

    public function satuan_jual()
    {
        return $this->belongsTo(Satuan::class, 'satuan_jual_id');
    }

    public function satuan_stock()
    {
        return $this->belongsTo(Satuan::class, 'satuan_stock_id');
    }

    public function jenis_barang()
    {
        return $this->belongsTo(JenisBarang::class);
    }

    public function subjenis_barang()
    {
        return $this->belongsTo(SubjenisBarang::class);
    }
}
