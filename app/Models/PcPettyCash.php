<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcPettyCash extends Model
{
    protected $guarded = [];
    protected $table = 'pc_petty_cashes';

    protected $fillable = [
        'branch_id',
        'user_id',
        'tanggal',
        'nominal',
        'flowtype',
        'approved_ma',
        'approved_fin',
        'created_by',
        'updated_by',
    ];
    // flowtype : 1 - drop (in) // 2 - use (out) // 3 - retur (out)

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
