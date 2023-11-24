<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Sight;
use App\Models\Event;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withPivot('last')->withTimestamps();
    }

    public function sights(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Sight::class)->withPivot('last')->withTimestamps();
    }

    public function historyStatuses(){
        return $this->hasMany(HistoryStatusContent::class);
    }
}
