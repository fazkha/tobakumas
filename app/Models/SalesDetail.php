<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'sales_detail';

    protected $fillable = [
        'sales_id',
        'marker_id',
        'jam',
        'lat',
        'lng',
        'hasil',
        'catatan',
        'created_by',
        'updated_by',
    ];
}
