<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GCountry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'description','initials'
    ];
}
