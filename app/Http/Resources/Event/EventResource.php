<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $name
 * @property string $sponsor
 * @property string $description
 * @property string $materials
 * @property int $user_id
 * @property int $vk_post_id
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 */
class EventResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request):array
    {
        $arr = [
            'id '           => $this->id,
            'name'          => $this->name,
            'sponsor'       => $this->sponsor,
            'description'   => $this->description,
            'materials'     => $this->materials,
            'user_id'       => $this->user_id,
            'vk_post_id'    => $this->vk_post_id,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
        isset($this->statuses) ? $arr['statuses'] = $this->statuses : null;
        isset($this->files) ? $arr['files'] = $this->files : null;
        isset($this->author) ? $arr['author'] = $this->author : null;
        isset($this->types) ? $arr['types'] = $this->types : null;
        isset($this->price) ? $arr['price'] = $this->price : null;
        isset($this->likedUsers) ? $arr['likedUsers'] = $this->likedUsers : null;
        isset($this->favoritesUsers) ? $arr['favoritesUsers'] = $this->favoritesUsers : null;
        isset($this->places) ? $arr['places'] = $this->places : null;

        return $arr;
    }
}
