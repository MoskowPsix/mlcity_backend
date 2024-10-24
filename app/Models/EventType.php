<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    use HasFactory;

    protected $table = 'etypes';

    protected $fillable = [
        'name',
        'ico',
        'cult_id',
        'etype_id',
        'order'
    ];

    public function historyContents(){
        return $this->morphToMany(HistoryContent::class, "history_contentable");
    }


    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class,'events_etypes','etype_id','event_id');
    }
    public function etypes(): HasMany
    {
        return $this->hasMany(EventType::class, 'etype_id')->with('etypes');
    }
}
