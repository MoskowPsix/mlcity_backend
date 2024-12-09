<?php

namespace App\Contracts\Services\UserService;

use App\Contracts\Services\UserService\UserServiceInterface;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventLikedUserExists;
use App\Http\Requests\PageANDLimitRequest;
use App\Models\Event;
use App\Models\Sight;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class UserService implements UserServiceInterface
{

    public function get(): array|User
    {
        $user_id = auth('api')->user()->id;
        return User::with('roles', 'socialAccount')->findOrFail($user_id);
    }

    public function getSocialAccountByUserId(): SocialAccount
    {
        $user_id = auth('api')->user()->id;
        return SocialAccount::where('user_id', $user_id)->firstOrFail();
    }

    public function getFavoriteEventsForIds(PageANDLimitRequest $request): object
    {
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;
        $user = auth('api')->user();

        return app(Pipeline::class)
                ->send($user->favoriteEvents())
                ->through([
                    EventLikedUserExists::class,
                    EventFavoritesUserExists::class
                ])
                ->via('apply')
                ->then(function ($favoriteEvents) use ($page, $limit){
                    return $favoriteEvents->with('prices')->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
                });
    }

    public function getLikedEventsForIds(PageANDLimitRequest $request): object
    {
        $user = auth('api')->user();
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        return app(Pipeline::class)
            ->send($user->likedEvents())
            ->through([
                EventLikedUserExists::class,
                EventFavoritesUserExists::class
            ])
            ->via('apply')
            ->then(function ($favoriteEvents) use ($page, $limit){
                return $favoriteEvents->with('prices')->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
            });
    }

    public function getFavoriteSightsForIds(PageANDLimitRequest $request): object
    {
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;
        $user = auth('api')->user();

        return app(Pipeline::class)
                ->send($user->favoriteSights())
                ->through([
                    EventLikedUserExists::class,
                    EventFavoritesUserExists::class
                ])
                ->via('apply')
                ->then(function ($favoriteSights) use ($page, $limit){
                    return $favoriteSights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
                });
    }

    public function getLikedSightsForIds(PageANDLimitRequest $request): object
    {
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;
        $user = auth('api')->user();

        return app(Pipeline::class)
            ->send($user->likedSights())
            ->through([
                EventLikedUserExists::class,
                EventFavoritesUserExists::class
            ])
            ->via('apply')
            ->then(function ($favoriteSights) use ($page, $limit){
                return $favoriteSights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
            });
    }

    public function toggleLikedEvent(int $id): bool
    {
        DB::beginTransaction();
        try {
            $user = auth("api")->user();
            $user->likedEvents()->toggle($id); // верно
            $event = Event::find($id); // верно

            if ($user->likedEvents()->where('event_id', $id)->exists()) {
                $event->likes()->where('event_id', $id)->exists()
                    ? $event->likes->increment('local_count')
                    : $event->likes()->create(["local_count" => 1]);
            } else if ($event->likes()->where('event_id', $id)->exists()) {
                if ($event->likes->local_count > 0)
                    $event->likes->decrement('local_count');
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }

    }

    public function toggleLikedSight(int $id): bool
    {
        DB::beginTransaction();
        try {
            $user = auth("api")->user();
            $user->likedSights()->toggle($id); // верно
            $sight = Sight::find($id); // верно

            if ($user->likedSights()->where('sight_id', $id)->exists()) {
                $sight->likes()->where('sight_id', $id)->exists()
                    ? $sight->likes->increment('local_count')
                    : $sight->likes()->create(["local_count" => 1]);
            } else if ($sight->likes()->where('sight_id', $id)->exists()) {
                if ($sight->likes->local_count > 0)
                    $sight->likes->decrement('local_count');
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function toggleFavoriteEvent(int $id): bool
    {
        DB::beginTransaction();
        try {
            auth("api")->user()->favoriteEvents()->toggle($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function toggleFavoriteSight(int $id): bool
    {
        DB::beginTransaction();
        try {
            auth('api')->user()->favoriteSights()->toggle($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function deleteForUsers(): bool
    {
        DB::beginTransaction();
        try {
            auth('api')->user()->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateUser($request)
    {
        // TODO: Implement updateUser() method.
    }

    public function addOrganization($usr_id, $request)
    {
        // TODO: Implement addOrganization() method.
    }

    public function getOrganizations($request)
    {
        // TODO: Implement getOrganizations() method.
    }

    public function acceptAgreement($reqeust)
    {
        // TODO: Implement acceptAgreement() method.
    }

    public function checkAgreement($reqeust, $agreement_id)
    {
        // TODO: Implement checkAgreement() method.
    }

    public function checkUserHaveOrganizations($request)
    {
        // TODO: Implement checkUserHaveOrganizations() method.
    }
}
