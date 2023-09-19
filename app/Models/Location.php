<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function locationsChildren(): hasMany
    {
        return $this->hasMany(Location::class)->with('locationsChildren');
    }
    public function locationParent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
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
