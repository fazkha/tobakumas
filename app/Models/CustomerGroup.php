<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $guarded = [];
    protected $table = 'customer_groups';

    protected $fillable = [
        'nama',
        'keterangan',
        'isactive',
        'created_by',
        'updated_by',
    ];
}
