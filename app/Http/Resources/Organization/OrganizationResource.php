<?php

namespace App\Http\Resources\Organization;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'inn'           => $this->inn,
            'ogrn'          => $this->ogrn,
            'number'        => $this->number,
            'description'   => $this->description,
            'user'          => $this->whenLoaded('user'),
            'users'         => $this->whenLoaded('users')
        ];
        return $response;
    }
}
