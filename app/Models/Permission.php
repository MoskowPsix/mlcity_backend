<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'description'
    ];

    public function organizations(){
        return $this->belongsToMany(Organization::class, "organization_permission_user", "permission_id","organization_id");
    }
}
