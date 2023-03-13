<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventType;
use App\Models\Status;
use App\Models\User;
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
        return $this->belongsToMany(Status::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(EventFile::class);
    }

}
