<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventType;
use App\Models\Status;
use App\Models\EventFile;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sponsor',
        'latitude',
        'longitude',
        'city',
        'address',
        'description',
        'price',
        'materials',
        'date_start',
        'date_end',
        'user_id',
        'vk_post_id',
    ];

    public function types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(EventType::class, 'events_etypes','event_id','etype_id');
    }

    public function statuses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class)->withPivot('last')->withTimestamps();
    }

    public function firstStatus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class)->orderByPivot('created_at', 'asc')->first();
    }

    public function lastStatus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class)->orderByPivot('created_at', 'desc')->first();
    }

    //У кого в избранном
    public function favoritesUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

   public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
   {
        return $this->belongsTo(User::class);
   }

    public function files(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(EventFile::class);
    }

}
