<?php

namespace App\Models;

use App\Models\Event;
use App\Models\EventTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use League\MimeTypeDetection\FinfoMimeTypeDetector;

class Place extends Model
{
    use HasFactory;

    protected $table = 'places';
    protected $fillable = [
        'event_id',
        'sight_id',
        'location_id',
        'latitude',
        'longitude',
        'address'
    ];

    // public function sight () {
    //     $this->belongsTo(Sight::class, 'sight_id');
    // }
    public function eventTypes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id')->with('types');
    }
    public function event() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id')->with('files', 'author', 'price')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
    }
    public function seances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Seance::class);
    }
    public function location() {
        return $this->belongsTo(Location::class, 'location_id')->with('locationParent');
    }

    public function historyPlaces(){
        return $this->hasMany(HistoryPlace::class);
    }
}
