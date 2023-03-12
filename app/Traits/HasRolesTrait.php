<?php

namespace App\Traits;

use App\Models\Role;

trait HasRolesTrait
{

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class,'users_roles');
    }

//    public function role(): \Illuminate\Database\Eloquent\Relations\HasOne
//    {
//        return $this->hasOne(Role::class,'users_roles');
//    }

    public function hasRole(... $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }
        return false;
    }
}
