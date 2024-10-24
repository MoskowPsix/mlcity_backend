<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        "email",
        "url",
        "user_id",
        "organization_id",
        "token"
    ];

    public function userPermissions(){
        return $this->belongsToMany(Permission::class, "organization_invite_permission_user","organization_invite_id","permission_id");
    }
}
