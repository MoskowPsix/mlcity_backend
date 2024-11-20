<?php

namespace App\Contracts\Services\UserService;

use App\Http\Requests\PageANDLimitRequest;
use App\Http\Resources\Organization\getUserOrganizations\GetUserOrganizationsOrganizationSuccessResource;
use App\Models\SocialAccount;
use App\Models\User;

interface UserServiceInterface
{
    public function get(): array|User;
    public function getSocialAccountByUserId():SocialAccount;
    public function getFavoriteEventsForIds(PageANDLimitRequest $request): object;
    public function getLikedEventsForIds(PageANDLimitRequest $request): object;
    public function getFavoriteSightsForIds(PageANDLimitRequest $request): object;
    public function getLikedSightsForIds(PageANDLimitRequest $request): object;
    public function toggleLikedEvent(int $id): bool;
    public function toggleLikedSight(int $id): bool;
    public function toggleFavoriteEvent(int $id): bool;
    public function toggleFavoriteSight(int $id): bool;
    public function deleteForUsers(): bool;
    public function updateUser($request);
    public function addOrganization($usr_id, $request);
    public function getOrganizations($request);
    public function acceptAgreement($reqeust);
    public function checkAgreement($reqeust, $agreement_id);
    public function checkUserHaveOrganizations( $request);
}
