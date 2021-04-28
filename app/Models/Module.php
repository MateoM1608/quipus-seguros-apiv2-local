<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Module extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'name', 'parent', 'title', 'url', 'icon', 'class', 'badge', 'wrapper', 'variant', 'attributes', 'divider', 'show',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getAttributesAttribute($value)
    {
        return $value ? json_decode($value) : json_decode('{}');
    }

    public function getBadgeAttribute($value)
    {
        return json_decode($value);
    }

    public function getActionsAttribute($value)
    {
        return json_decode($value);
    }

    public function getWrapperAttribute($value)
    {
        return json_decode($value);
    }

    public function permission()
    {
        return $this->hasMany(Module::class, 'parent', 'id')
            ->join('permissions', 'module_id', 'modules.id')
            ->where('permissions.user_id', auth()->user()->id)
            ->select([
                "modules.id",
                "modules.description",
                "modules.name",
                "modules.parent",
                "modules.url",
                "modules.icon",
                "modules.image",
                "modules.class",
                "modules.badge",
                "modules.wrapper",
                "modules.variant",
                "modules.attributes",
                "modules.divider",
                "modules.method",
                "modules.show",
                "permissions.actions",
                "permissions.user_id",
                "permissions.id AS permission_id",
            ])
            ->orderBy('order', 'asc')
            ->with(['permission']);
    }

    public function children()
    {
        return $this->hasMany(Module::class, 'parent', 'id')
            ->select([
                "modules.id",
                "modules.description",
                "modules.name",
                "modules.parent",
                "modules.url",
                "modules.icon",
                "modules.image",
                "modules.class",
                "modules.badge",
                "modules.wrapper",
                "modules.variant",
                "modules.attributes",
                "modules.divider",
                "modules.method",
                "modules.show",
            ])
            ->orderBy('order', 'asc')
            ->with(['children']);
    }

    public function permissionProfile()
    {
        return $this->hasMany(Module::class, 'parent', 'id')
            ->join('permission_profiles', 'module_id', 'modules.id')
            ->where('permission_profiles.profile_id', \Request::instance()->profile_id)
            ->select([
                "modules.id",
                "modules.description",
                "modules.name",
                "modules.parent",
                "modules.url",
                "modules.icon",
                "modules.image",
                "modules.class",
                "modules.badge",
                "modules.wrapper",
                "modules.variant",
                "modules.attributes",
                "modules.divider",
                "modules.method",
                "modules.show",
                "permission_profiles.actions",
                "permission_profiles.profile_id",
                "permission_profiles.id AS permission_profile_id",
            ])
            ->orderBy('order', 'asc')
            ->with(['permissionProfile']);
    }
}
