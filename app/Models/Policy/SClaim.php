<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SClaim extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'claim_number', 'claim_date', 'notice_date', 'claim_value', 'paid_value', 'payment_date', 'objection_date', 'claim_description', 's_policy_id', 'claim_status'
    ];

    public function sPolicy()
    {
        return $this->belongsTo(SPolicy::class, 's_policy_id');
    }
}
