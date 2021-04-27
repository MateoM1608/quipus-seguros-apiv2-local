<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $table = 'permissions';

    public function getActionsAttribute($value)
    {
        return json_decode($value);
    }

    public function module()
    {
        return $this->hasMany(Module::class, 'id', 'module_id');
    }
}
