<?php

namespace App\Models;

use App\Events\Place\PlaceCreated;
use App\Models\Event;
use App\Models\EventTypes;
use App\Traits\SearchableContentTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use League\MimeTypeDetection\FinfoMimeTypeDetector;

class Place extends ElasticsearchModel
{
    use SearchableContentTrait;
    use HasFactory;

    protected $table = 'places';
    protected $fillable = [
        'event_id',
        'sight_id',
        'location_id',
        'latitude',
        'longitude',
        'address',
        'timezone_id',
        'cult_id'
    ];

    protected $spatialFields = [
        'location',
    ];

    protected static function booted(){
        static::created(function ($model){
            event(new PlaceCreated($model));
        });
    }

    public function eventTypes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id')->with('types');
    }
    public function eventStatuses(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id')->with('statuses');
    }
    public function event() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
        // return $this->belongsTo(Event::class, 'event_id', 'id')->with('files', 'author', 'price')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
    }

    public function eventWithLikes(){
        return $this->belongsTo(Event::class, 'event_id', 'id')->with('files')->withCount('likedUsers', 'favoritesUsers', 'comments');
    }
    public function seances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Seance::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class)->with('locationParent');
    }

    public function timezones(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Timezone::class, "timezone_id");
    }

    public function historyPlaces(){
        return $this->hasMany(HistoryPlace::class);
    }
    public function sight(): HasOne
    {
        return $this->hasOne(Sight::class);
    }
}
