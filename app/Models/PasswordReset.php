<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $connection = 'seguros';

    const UPDATED_AT = null;

    protected $fillable = [
        'email', 'token'
    ];

    protected $hidden = [
        'email', 'token'
    ];
}
