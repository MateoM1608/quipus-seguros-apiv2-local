<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UploadedInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nit','type','path', 'log', 'total_records', 'inserted_registry', 'bad_records', 'user_id',
    ];
}
