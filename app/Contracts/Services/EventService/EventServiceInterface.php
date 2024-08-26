<?php

namespace App\Contracts\Services\EventService;

use App\Http\Requests\Events\EventCreateRequest;
use App\Http\Requests\Events\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use App\Models\Event;

interface EventServiceInterface
{
    public function store(EventCreateRequest $data): Event;
    public function get($data);
    public function getUserEvents($data);
    public function getById(int $id): Event;
    public function setEvenUserLiked(SetEventUserLikedRequest $request): bool;
    public function checkLiked(int $id): bool;
    public function checkFavorite(int $id): bool;
    public function showForMap(int $id): Event;
    public function getEventUserLiked(int $id, PageANDLimitRequest $request): object;
    public function getEventUserFavoritesIds($id, PageANDLimitRequest $request): object;

}
