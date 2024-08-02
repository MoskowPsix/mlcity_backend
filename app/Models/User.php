<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Email;
use App\Models\Location;
use App\Models\Phone;
use App\Traits\HasRolesTrait;
use \Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\SocialAccount;
use App\Models\Role;
use App\Models\Event;
use App\Models\Sight;
use App\Models\EventLike;
use App\Models\SightLike;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'number',
        'password',
        'avatar',
        'location_id',
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

    protected $dates = ['deleted_at'];

    /**
     * Summary of ecode
     * @return HasMany
     */
    public function ecode(): HasMany
    {
        return $this->hasMany(EmailCode::class);
    }
    /**
     * Summary of socialAccount
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SocialAccount::class);
    }

    //избранные события юзера
    /**
     * Summary of favoriteEvents
     * @return BelongsToMany
     */
    public function favoriteEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class,'event_user_favorite', 'user_id','event_id')->with('types', 'files','statuses', 'author')->withCount('likedUsers', 'favoritesUsers');
    }

    //избранные достопримечательности юзера
    /**
     * Summary of favoriteSights
     * @return BelongsToMany
     */
    public function favoriteSights(): BelongsToMany
    {
        return $this->belongsToMany(Sight::class,'sight_user_favorite', 'user_id','sight_id')->with('types', 'files','statuses', 'author')->withCount('likedUsers', 'favoritesUsers');
    }

    //События созданные юзером
    /**
     * Summary of events
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    //Достопримечательности созданные юзером
    /**
     * Summary of sights
     * @return HasMany
     */
    public function sights(): HasMany
    {
        return $this->hasMany(Sight::class);
    }

    /// события юзера которы лайкнул
    /**
     * Summary of likedEvents
     * @return BelongsToMany
     */
    public function likedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class,'event_user_liked')->withTimestamps()->with('types', 'files','statuses', 'author')->withCount('likedUsers', 'favoritesUsers');
    }

    // достопримечательности юзера, которые лайкнул
    /**
     * Summary of likedSights
     * @return BelongsToMany
     */
    public function likedSights(): BelongsToMany
    {
        return $this->belongsToMany(Sight::class, 'sight_user_liked')->withTimestamps()->with('types', 'files', 'statuses', 'author')->withCount('likedUsers', 'favoritesUsers');
    }
    /**
     * Summary of locations
     * @return BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, $table="users_roles");
    }


    public function permissionsInOrganization(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, "organization_permission_user", "user_id", "permission_id")->withPivot("organization_id");
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, "organization_permission_user", "user_id","organization_id");
    }

    public function userAgreements(): BelongsToMany
    {
        return $this->belongsToMany(UserAgreement::class);
    }

}
