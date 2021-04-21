<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SAnnex extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'annex_number',
        'annex_expedition',
        'annex_start',
        'annex_end',
        'annualized_premium',
        'annex_premium',
        'annex_tax',
        'annex_expedition_cost',
        'annex_other_cost',
        'annex_total_value',
        'annex_description',
        'annex_commission',
        'annex_paid',
        'commission_paid',
        'annex_type',
        's_policy_id',
        'annex_print',
        'annex_printed',
        'annex_email',
        'annex_delivered'
    ];

    public function sPolicy()
    {
        return $this->belongsTo(SPolicy::class, 's_policy_id');
    }
}
