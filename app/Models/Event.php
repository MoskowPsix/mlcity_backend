<?php

namespace App\Models;
use App\Models\Place;
use App\Models\Price;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventType;
use App\Models\Status;
use App\Models\EventFile;
use App\Models\EventLike;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
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
    ];

    public function types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(EventType::class, 'events_etypes','event_id','etype_id');
    }

    public function statuses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class)->withPivot('last', 'descriptions')->orderBy('pivot_created_at', 'desc')->withTimestamps();
    }

    //У кого в избранном
    public function favoritesUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'event_user_favorite')->withTimestamps();
    }

    //Кто лайкнул
    public function likedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'event_user_liked')->withTimestamps();
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
    // Подтягиваем маркеры
    public function places(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Place::class);
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

    public function historyContents(): MorphMany{
        return $this->morphMany(HistoryContent::class, "history_contentable");
    }
}
