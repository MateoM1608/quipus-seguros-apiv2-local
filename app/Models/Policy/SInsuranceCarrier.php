<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SInsuranceCarrier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'insurance_carrier', 'identification'
    ];
}
