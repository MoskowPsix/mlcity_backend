<?php

namespace App\Contracts\Services\OrganizationService;

use App\Filters\Event\EventExpired;
use App\Filters\Event\EventFiles;
use App\Filters\Event\EventPrices;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Pipeline\Pipeline;

interface OrganizationServiceInterface
{
    public function addUserToOrganization(int $userId, int $organizationId): void;
    public function getEvents(int $organizationId, $data): object;
    public function store($data): Organization;
    public function delete(int $id): bool;

}
