<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COperation extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'operation_name',
		'operation_description',
		'start_date',
		'end_date',
		'user_id',
		'c_operation_type_id'
    ];
   
    public function cOperationType()
    {
        return $this->belongsTo(COperationType::class, 'c_operation_type_id');
    }
}
