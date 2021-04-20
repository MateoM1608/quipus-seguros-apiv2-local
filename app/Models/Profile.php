<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

     protected $fillable = [
        'description',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
