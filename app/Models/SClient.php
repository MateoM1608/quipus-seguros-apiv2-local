<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SClient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identification','first_name', 'last_name', 'birthay', 'adress', 'fix_phone', 'cel_phone', 'email', 'g_city_id', 'g_identification_type_id', 'observations', 'habeas_data_terms', 'habeas_data_email','habeas_data_sms','habeas_data_phone'
    ];

    public function gCity()
    {
        return $this->belongsTo(GCity::class, 'g_city_id');
    }

    public function gIdentificationType()
    {
        return $this->belongsTo(GIdentificationType::class, 'g_identification_type_id');
    }
}
