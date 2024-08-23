<?php

namespace App\Http\Resources\Organization;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->whenLoaded('user'));
        $response = [
            'id'                => $this->id,
            'name'              => $this->name,
            'avatar'            => $this->avatar,
            'description'       => $this->description,
            'user'              => $this->whenLoaded('user'),
            'users'             => $this->whenLoaded('users'),
            'permissions'       => $this->whenLoaded('permissions'),
            'users_permissions' => $this->whenLoaded('usersPermissions'),
            'types'             => $this->whenLoaded('stypes'),
            'location'          => $this->whenLoaded('usersPermissions'),
        ];
        return $response;
    }
}
