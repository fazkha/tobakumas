<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brandivjab extends Model
{
    protected $guarded = [];
    protected $table = 'brandivjabs';

    protected $fillable = [
        'branch_id',
        'division_id',
        'jabatan_id',
        'atasan_id',
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

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
