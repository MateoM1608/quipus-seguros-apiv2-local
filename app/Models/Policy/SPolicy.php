<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SPolicy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'policy_number', 'expedition_date', 's_branch_id', 's_client_id', 'g_vendor_id', 'policy_state', 'payment_periodicity', 's_agency_id'
    ];

    public function sClient()
    {
        return $this->belongsTo(\App\Models\SClient::class, 's_client_id');
    }

    public function sAgency()
    {
        return $this->belongsTo(SAgency::class, 's_agency_id');
    }

    public function gVendor()
    {
        return $this->belongsTo(GVendor::class, 'g_vendor_id');
    }

    public function sBranch()
    {
        return $this->belongsTo(SBranch::class, 's_branch_id');
    }

    public function sRisk()
    {
        return $this->hasMany(SRisk::class);
    }

    public function sClaims()
    {
        return $this->hasMany(SClaim::class);
    }
    public function sAnnex()
    {
        return $this->hasMany(SAnnex::class);
    }
}
