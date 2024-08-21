<?php

namespace App\Contracts\Services\EventService;

use App\Models\Event;

interface EventServiceInterface
{
    public function store($data): Event;
}
