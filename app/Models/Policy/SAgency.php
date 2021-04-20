<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SAgency extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'agency_name', 'identification', 'agency_commission'
    ];
}
