<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COperationComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'comment_description',
		'comment_date',
		'user_id',
        'c_operation_id'
    ];

    public function cOperation()
    {
        return $this->belongsTo(COperation::class, 'c_operation_id');
    }
}
