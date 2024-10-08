<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $email_verified_at
 * @property mixed $avatar
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                            => $this->id,
            'name'                          => $this->name,
            'email'                         => $this->email,
            'email_verified_at'             => $this->email_verified_at,
            'avatar'                        => $this->avatar,
            'socialAccount'                 => $this->whenLoaded('socialAccount'),
            'favoriteEvents'                => $this->whenLoaded('favoriteEvents'),
            'favoriteSights'                => $this->whenLoaded('favoriteSights'),
            'events'                        => $this->whenLoaded('events'),
            'sights'                        => $this->whenLoaded('sights'),
            'likedEvents'                   => $this->whenLoaded('likedEvents'),
            'likedSights'                   => $this->whenLoaded('likedSights'),
            'locations'                     => $this->whenLoaded('locations'),
            'role'                          => $this->whenLoaded('role'),
            'permissionsInOrganization'     => $this->whenLoaded('permissionsInOrganization'),
            'organizations'                 => $this->whenLoaded('organizations'),
            'userAgreements'                => $this->whenLoaded('userAgreements'),
        ];












    }
}
