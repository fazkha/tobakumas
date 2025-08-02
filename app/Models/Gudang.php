<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $guarded = [];
    protected $table = 'gudangs';

    protected $fillable = [
        'branch_id',
        'kode',
        'nama',
        'alamat',
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
