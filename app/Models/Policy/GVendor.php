<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GVendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identification', 'first_name', 'last_name', 'birthday', 'cellphone', 'email', 'commission', 'g_identification_type_id'
    ];

    public function gIdentificationType()
    {
        return $this->belongsTo(\App\Models\GIdentificationType::class, 'g_identification_type_id');
    }
}
