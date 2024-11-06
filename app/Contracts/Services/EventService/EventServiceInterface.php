<?php

namespace App\Contracts\Services\EventService;

use App\Http\Requests\Event\EventCreateRequest;
use App\Http\Requests\Event\EventForAuthorReqeust;
use App\Http\Requests\Event\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\SearchContentForTextRequest;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Sight;
use App\Models\User;

interface EventServiceInterface
{
    public function store(EventCreateRequest $data): Event;
    public function get($data);
    public function getSearchText(SearchContentForTextRequest $request): object;
//    public function searchForText($request)
    public function getUserEvents(EventForAuthorReqeust $data);
    public function getById(int $id): Event;
    public function setEvenUserLiked(SetEventUserLikedRequest $request): bool;
    public function checkLiked(int $id): bool;
    public function checkFavorite(int $id): bool;
    public function showForMap(int $id): Event;
    public function getEventUserLiked(int $id, PageANDLimitRequest $request): object;
    public function getEventUserFavoritesIds($id, PageANDLimitRequest $request): object;
    public function getOrganizationOfEvent($id);
    public function addStatus(int $Id);
    public function delete(int $Id): bool;
    public function addView(int $id): bool;
}
