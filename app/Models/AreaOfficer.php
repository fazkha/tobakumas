<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaOfficer extends Model
{
    protected $guarded = [];
    protected $table = 'area_officers';

    protected $fillable = [
        'pegawai_id',
        'customer_id',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
