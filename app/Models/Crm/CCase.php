<?php

namespace App\Models\Crm;

use App\Models\Policy\SPolicy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'c_type_case_id',
        's_client_id',
        's_policy_id',
        'c_type_case_stage_id',
        'c_case_area_id','risk',
        'description',
        'creator_user_id',
        'creator_name',
        'assigned_user_id',
        'assigned_name',
        'projected_value',
        'real_value',
        'status_case',
        'closing_note',
        'expiration_date',
        'calification'
    ];

    public function cTypeCase()
    {
        return $this->belongsTo(CTypeCase::class, 'c_type_case_id');
    }
    public function cCaseStages()
    {
        return $this->belongsTo(CCaseStage::class, 'c_type_case_stage_id');
    }
    public function sClient()
    {
        return $this->belongsTo(\App\Models\SClient::class, 's_client_id');
    }
    public function sPolicy()
    {
        return $this->belongsTo(SPolicy::class, 's_policy_id');
    }
    public function cCaseNote()
    {
       return $this->hasMany(CCaseNote::class);
    }
    public function cCaseArea()
    {
       //return $this->hasMany(CCaseArea::class);
       return $this->belongsTo(CCaseArea::class, 'c_case_area_id');
    }
}
