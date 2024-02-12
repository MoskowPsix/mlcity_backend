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
}
