<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $guarded = [];
    protected $table = 'password_resets';

    protected $fillable = [
        'email',
        'otp',
        'expired_at',
        'verified'
    ];
}
