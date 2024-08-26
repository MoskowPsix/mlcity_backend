<?php

namespace App\Contracts\Services\OrganizationService;

use App\Models\Event;
use App\Models\Organization;


class OrganizationService {
    public function addUserToOrganization(int $userId, int $organizationId): void
    {

    }

    public function getEvents(int $organizationId)
    {
        $events = Event::where("organization_id", $organizationId)->get();

        return $events;
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
