<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PermissionProfile extends Model
{
    use SoftDeletes;

    public function getActionsAttribute($value)
    {
        return json_decode($value);
    }

    public function module()
    {
        return $this->hasMany(Module::class, 'id', 'module_id');
    }
}
