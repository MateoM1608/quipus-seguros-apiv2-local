<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CProcess extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'description',
		'start_date',
		'end_date',
		'sale_value',
		'open_close',
		'c_process_stage_id',
		's_client_id'
    ];
   
    public function cProcessStage()
    {
        return $this->belongsTo(CProcessStage::class, 'c_process_stage_id');
    }
}
