<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konversi extends Model
{
    protected $guarded = [];
    protected $table = 'konversis';

    protected $fillable = [
        'satuan_id',
        'satuan2_id',
        'operator',
        'bilangan',
        'isactive',
    ];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function satuan2()
    {
        return $this->belongsTo(Satuan::class, 'satuan2_id');
    }
}
