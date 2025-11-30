<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile_Mm extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'profiles';
    protected $connection = 'mm_db';

    protected $fillable = [
        'user_id',
        'branch_id',
        'jabatan_id',
        'nohp',
        'noktp',
        'alamat',
        'daerah_asal',
        'tanggal_lahir',
        'pendidikan',
        'lokasi',
        'profile_image',
        'tanggal_gabung',
        'isactive',
        'app_version',
        'created_by',
        'updated_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch_Mm::class, 'branch_id');
    }
}
