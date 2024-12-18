<?php

namespace App\Contracts\Services\SightService;

use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\SearchContentForTextRequest;
use App\Http\Requests\Sight\CreateSightRequest;
use App\Http\Requests\Sight\GetSightsForMapRequest;
use App\Http\Requests\Sight\GetSightsRequest;
use App\Models\Sight;
use Illuminate\Http\Request;

interface SightServiceInterface
{
    public function show(int $id): Sight;
    public function create(CreateSightRequest $request): Sight;
    public function getSights(GetSightsRequest $request): object;
    public function getSightsForMap(GetSightsForMapRequest $request): object;
    public function getSightsForAuthor(PageANDLimitRequest $request): object;
    public function showForCard(int $ig): Sight;
    public function checkLiked(int $id): bool;
    public function checkFavorite(int $id): bool;
    public function getEventsInSight(PageANDLimitRequest $request, int $id): object;
    public function getSightUserLikedIds(int $id, PageANDLimitRequest $request): object;
    public function getSightUserFavoritesIds($id, PageANDLimitRequest $request): object;

}
