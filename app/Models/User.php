<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasRolesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\SocialAccount;
use App\Models\Role;
use App\Models\Event;
use App\Models\Sight;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    protected $with = ['socialAccount'];

    public function socialAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SocialAccount::class);
    }

    //избранные события юзера
    public function favoriteEvents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    //избранные достопримечательности юзера
    public function favoriteSights(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Sight::class)->withTimestamps();
    }

    //События созданные юзером
    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Event::class);
    }

    //Достопримечательности созданные юзером
    public function sights(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sight::class);
    }

//    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Role::class);
//    }
}
