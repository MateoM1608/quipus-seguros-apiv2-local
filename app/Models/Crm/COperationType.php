<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class COperationType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'operation_type'
    ];
}
