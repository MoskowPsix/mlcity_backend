<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SightInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        "email",
        "url",
        "user_id",
        "sight_id",
        "token"
    ];

    public function userPermissions(){
        return $this->belongsToMany(Permission::class, "sight_invite_permission_user","sight_invite_id","permission_id");
    }
}
