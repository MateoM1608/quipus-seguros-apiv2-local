<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SPayment extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'payment_number',
        'payment_date',
        'premium_value',
        'tax_value',
        'total_value',
        's_annex_id',
        'payment_form'
    ];

    public function sAnnex()
    {
        return $this->belongsTo(SAnnex::class, 's_annex_id');
    }
}
