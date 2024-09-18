<?php

namespace App\Contracts\Services\OrganizationService;

use App\Filters\Event\EventExpired;
use App\Filters\Event\EventFiles;
use App\Filters\Event\EventPrices;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Pipeline\Pipeline;

class OrganizationService implements OrganizationServiceInterface
{
    public function addUserToOrganization(int $userId, int $organizationId): void
    {

    }

    public function getEvents(int $organizationId, $data): object
    {
        $events = Event::query()->where("organization_id", $organizationId);
        $page = $data->page;
        $limit = $data->limit && ($data->limit < 50)? $data->limit : 6;
        return app(Pipeline::class)
        ->send($events)
        ->through([
            EventPrices::class,
            EventFiles::class,
            EventExpired::class

        ])
        ->via("apply")
        ->then(function($event) use($page, $limit){
            return $event->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        });
    }

    public function store($data): Organization
    {
        $organization = Organization::create($data);
        if (isset($data['typeId'])) {
            $organization->types()->attach($data['typeId']);
        }
        if (isset($data['locationId'])) {
            $organization->locations()->attach($data['locationId']);
        }
        if (isset($data['avatar'])) {
            $this->saveLocalAvatar($organization, $data['avatar']);
        }

        return $organization;
    }
    public function delete(int $id): bool
    {
        try {
            $organization = Organization::where('id', $id);
            $organization->exists() ? null : throw new \Exception('Organization not found');
            if ($organization->firstOrFail()->sight()->firstOrFail()->user_id === auth('api')->user()->id) {
                Event::where('organization_id', $id)->delete();
                $organization->first()->sight()->delete();
                $organization->forceDelete();
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    private function saveLocalAvatar(Organization $org, $file): void
    {
        $path = $file->store('organization/avatar/' . $org->id, 'public');
        $org->update(['avatar' => '/storage/' . $path]);
    }
}
