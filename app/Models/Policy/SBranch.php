<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SBranch extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'commission', 'tax', 's_insurance_carrier_id', 'loss_coverage', 'cancellation_risk', 'cancellation'];

    public function sInsuranceCarrier()
    {
        return $this->belongsTo(SInsuranceCarrier::class, 's_insurance_carrier_id');
    }
}
