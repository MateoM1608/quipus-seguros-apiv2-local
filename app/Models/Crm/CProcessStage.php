<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CProcessStage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'process_stage'
    ];

    
}
