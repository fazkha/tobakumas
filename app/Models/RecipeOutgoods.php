<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeOutgoods extends Model
{
    protected $guarded = [];
    protected $table = 'recipe_outgoods';

    protected $fillable = [
        'recipe_id',
        'barang_id',
        'satuan_id',
        'kuantiti',
        'harga_satuan',
        'created_by',
        'updated_by',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
