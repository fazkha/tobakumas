<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'sales';

    protected $fillable = [
        'user_id',
        'branch_id',
        'tanggal',
        'waktu',
        'waktu_detik',
        'jarak',
        'created_by',
        'updated_by',
    ];
}
