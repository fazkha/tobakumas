<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOfficer extends Model
{
    protected $guarded = [];
    protected $table = 'delivery_officers';

    protected $fillable = [
        'branch_id',
        'pegawai_id',
        'tanggal',
        'no_order',
        'keterangan',
        'isdone',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
