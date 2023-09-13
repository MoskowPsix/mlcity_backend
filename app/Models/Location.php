<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'name',
        'time_zone',
        'location_id',
        'cult_id'
    ];

    public function locations(): hasMany
    {
        return $this->hasMany(Location::class);
    }

    public function user(): hasMany
    {
        return $this->hasMany(User::class);
    }

    public function sights(): hasMany
    {
        return $this->hasMany(Sights::class);
    }

    public function events(): hasMany
    {
        return $this->hasMany(Event::class);
    }

}
