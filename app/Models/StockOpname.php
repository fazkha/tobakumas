<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $guarded = [];
    protected $table = 'stock_opnames';

    protected $fillable = [
        'branch_id',
        'gudang_id',
        'tanggal',
        'petugas_1_id',
        'petugas_2_id',
        'tanggungjawab_id',
        'keterangan',
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

    public function petugas1()
    {
        return $this->belongsTo(Pegawai::class, 'petugas_1_id');
    }

    public function petugas2()
    {
        return $this->belongsTo(Pegawai::class, 'petugas_2_id');
    }

    public function tanggungjawab()
    {
        return $this->belongsTo(Pegawai::class, 'tanggungjawab_id');
    }
}
