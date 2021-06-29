<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CCaseNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'c_case_id', 'user_id', 'user_name', 'user_email', 'note', 'type_note','end_date', 'state'
    ];

    public function cCases()
    {
        return $this->belongsTo(CCase::class, 'c_case_id');
    }
}
