<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CTypeCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description'
    ];
}
