<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOfficer extends Model
{
    protected $guarded = [];
    protected $table = 'delivery_officers';

    protected $fillable = [
        'delivery_order_id',
        'area_officer_id',
    ];
}
