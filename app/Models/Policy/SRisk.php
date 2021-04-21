<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SRisk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'risk_number', 'risk_description', 'risk_premium', 's_policy_id'
    ];

    public function sPolicy()
    {
        return $this->belongsTo(SPolicy::class, 's_policy_id');
    }
}
