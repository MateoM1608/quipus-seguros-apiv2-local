<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SCommission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'commission_number',
        'commission_date',
        'commission_value',
        's_annex_id',
        's_payroll_id',
        'g_vendor_id',
        'vendor_commission_paid',
        'agency_commission',
        'payment_day',
        'status_payment',
    ];

    public function sAnnex()
    {
        return $this->belongsTo(\App\Models\Policy\SAnnex::class, 's_annex_id');
    }
    public function gVendor()
    {
        return $this->belongsTo(\App\Models\Policy\GVendor::class, 'g_vendor_id');
    }
}
