<?php

namespace App\Contracts\Services\OrganizationService;

use App\Models\Event;
use App\Models\Organization;


class OrganizationService {
    public function addUserToOrganization(int $userId, int $organizationId): void {

    }

    public function getEvents(int $organizationId) {
        $events = Event::where("organization_id", $organizationId)->get();

        return $events;
    }
}
