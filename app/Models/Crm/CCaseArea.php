<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCaseArea extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'description'
    ];
}
