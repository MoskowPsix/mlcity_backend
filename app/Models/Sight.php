<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SightType;
use App\Models\Status;
use App\Models\SightFile;
use App\Models\SightLike;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sight extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sponsor',
        'latitude',
        'longitude',
        'location_id',
        'address',
        'description',
        'price',
        'materials',
        'user_id',
        'cult_id',
        'work_time',
        'afisha7_id',
    ];

    protected $with = ['proganization'];

    public function types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SightType::class,'sights_stypes','sight_id','stype_id');
    }

    public function statuses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class)->withPivot('last')->orderBy('pivot_created_at', 'desc')->withTimestamps();
    }

//    public function firstStatus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Status::class)->orderByPivot('created_at', 'asc')->first();
//    }
//
//    public function lastStatus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Status::class)->orderByPivot('created_at', 'desc')->first();
//    }

    //У кого в избранном
    public function favoritesUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'sight_user_favorite')->withTimestamps();
    }

    //Кто лайкнул
    public function likedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'sight_user_liked')->withTimestamps();
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');

    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SightFile::class)->with('file_types');
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SightLike::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->where('comment_id')->with('user', 'comments');
    }
    public function viewsUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(View::class);
    }
    public function locations(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function historyContents(): MorphMany
    {
        return $this->morphMany(HistoryContent::class, "history_contentable");
    }

    public function prices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, "places", "sight_id", "event_id")->with("files");
    }

    public function  organization()
    {
        return $this->hasOne(Organization::class);
    }
}
