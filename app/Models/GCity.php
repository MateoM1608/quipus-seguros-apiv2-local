<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GCity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description','initials', 'g_country_id'
    ];

    public function gCountry()
    {
        return $this->hasOne(GCountry::class, 'id');
    }
}
