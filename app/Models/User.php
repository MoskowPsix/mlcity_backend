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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    protected $dates = ['deleted_at'];

//    protected $with = ['socialAccount'];

    /**
     * Summary of pcode
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pcode(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PhoneCode::class);
    }
    /**
     * Summary of ecode
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ecode(): \Illuminate\Database\Eloquent\Relations\HasMany
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteEvents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class,'event_user_favorite', 'user_id','event_id')->with('types', 'files','statuses', 'author', 'comments')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
    }

    //избранные достопримечательности юзера
    /**
     * Summary of favoriteSights
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteSights(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Sight::class,'sight_user_favorite', 'user_id','sight_id')->with('types', 'files','statuses', 'author', 'comments')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
    }

    //События созданные юзером
    /**
     * Summary of events
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Event::class);
    }

    //Достопримечательности созданные юзером
    /**
     * Summary of sights
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sights(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sight::class);
    }

    /// события юзера которы лайкнул
    /**
     * Summary of likedEvents
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likedEvents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class,'event_user_liked')->withTimestamps()->with('types', 'files','statuses', 'author', 'comments')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
    }

    // достопримечательности юзера, которые лайкнул
    /**
     * Summary of likedSights
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likedSights(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Sight::class,'sight_user_liked')->withTimestamps()->with('types', 'files','statuses', 'author', 'comments')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
    }

//    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Role::class);
//    }
    /**
     * Summary of locations
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function locations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }

    public function role(){
        return $this->belongsToMany(Role::class, $table="users_roles");
    }


    public function permissionsInOrganization(){
        return $this->belongsToMany(Permission::class, "user_organization", "user_id", "permission_id")->withPivot("organization_id");
    }

    public function organizations(){
        return $this->belongsToMany(Organization::class, "user_organization", "user_id","organization_id");
    }

    public function userAgreements(){
        return $this->belongsToMany(UserAgreement::class);
    }

}
