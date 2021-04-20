<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Pivot
{
    use SoftDeletes;

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
