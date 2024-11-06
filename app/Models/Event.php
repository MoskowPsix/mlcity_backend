<?php

namespace App\Models;

use App\Traits\SearchableContentTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends ElasticsearchModel
{
    use SearchableContentTrait;
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'sponsor',
        'description',
        'price',
        'materials',
        'date_start',
        'date_end',
        'user_id',
        'vk_post_id',
        'cult_id',
        'creates_at',
        'updated_at',
        'afisha7_id',
        'min_cult_id',
        'organization_id',
        'age_limit',
    ];
    protected array $dates = ['date_start', 'date_end'];

    public function types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(EventType::class, 'events_etypes', 'event_id', 'etype_id');
    }

    public function statuses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class)->withPivot('last', 'descriptions')->orderBy('pivot_created_at', 'desc')->withTimestamps();
    }


    //    public function firstStatus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //    {
    //        return $this->belongsToMany(Status::class)->orderByPivot('created_at', 'asc')->first();
    //    }
    //
    public function lastStatus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class)->wherePivot('last', true);
    }

    //У кого в избранном
    public function favoritesUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user_favorite')->withTimestamps();
    }

    //Кто лайкнул
    public function likedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user_liked')->withTimestamps();
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(EventFile::class)->with('file_types');
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EventLike::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->where('comment_id')->with('user', 'comments');
    }
    // public function views(): \Illuminate\Database\Eloquent\Relations\HasOne
    // {
    //     return $this->hasOne(View::has('id'));
    // }
    public function viewsUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(View::class);
    }

    // public function locations(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    // {
    //     return $this->belongsTo(Location::class, 'location_id');
    // }
    // Подтягиваем маркеры
    public function places(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Place::class);
    }
    public function locations(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Location::class, Place::class, firstKey: 'event_id', secondKey: 'id', secondLocalKey: 'location_id');
    }
    public function locationsBelongToMany(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'places');
    }
    public function placesFull(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Place::class)->with('seances', 'location', "timezones");
    }
    // Подтягиваем цену
    public function price(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function prices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function historyContents(): MorphMany
    {
        return $this->morphMany(HistoryContent::class, "history_contentable");
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function viewCount(): HasOne
    {
        return $this->hasOne(ViewCount::class);
    }
}
