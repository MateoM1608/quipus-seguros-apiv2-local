<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GCountry extends Model
{
    use SoftDeletes;
  
    protected $fillable = [
    'description','initials'
    ];
}
