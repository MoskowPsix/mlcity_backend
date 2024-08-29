<?php

namespace App\Contracts\Services\OrganizationService;

use App\Filters\Event\EventFiles;
use App\Filters\Event\EventPrices;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Pipeline\Pipeline;

class OrganizationService {
    public function addUserToOrganization(int $userId, int $organizationId): void
    {

    }

    public function getEvents(int $organizationId, $data)
    {
        $events = Event::query()->where("organization_id", $organizationId);
        $page = $data->page;
        $limit = $data->limit && ($data->limit < 50)? $data->limit : 6;
        $response =
        app(Pipeline::class)
        ->send($events)
        ->through([
            EventPrices::class,
            EventFiles::class
        ])
        ->via("apply")
        ->then(function($event) use($page, $limit){
            return $event->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        });

        return $response;
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

    private function saveLocalAvatar(Organization $org, $file): void
    {
        $path = $file->store('organization/avatar/' . $org->id, 'public');
        $org->update(['avatar' => '/storage/' . $path]);
    }
}
