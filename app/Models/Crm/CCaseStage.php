<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CCaseStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description', 'c_type_case_id'
    ];

    public function cTypeCase()
    {
        return $this->belongsTo(CTypeCase::class, 'c_type_case_id');
    }
}
