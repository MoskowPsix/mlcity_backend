<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'inn',
        'ogrn',
        'kpp',
        'user_id',
        'address',
        'number',
        'description'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function usersPermissions() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withPivot('user_id');
    }
}
