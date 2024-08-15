<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'avatar',
        'user_id',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "organization_permission_user", "organization_id", "permission_id");
    }
    public function users()
    {
        return $this->belongsToMany(User::class, "organization_permission_user", "organization_id", "user_id")->distinct();
    }

    public function usersPermissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withPivot('user_id');
    }

    public function location(): BelongsToMany
    {
        return $this->belongsToMany(Location::class,  'organization_location', 'organization_id', 'location_id');
    }

    public function stypes(): BelongsToMany
    {
        return $this->belongsToMany(SightType::class, 'organization_stype', 'organization_id', 'stype_id');
    }
}
