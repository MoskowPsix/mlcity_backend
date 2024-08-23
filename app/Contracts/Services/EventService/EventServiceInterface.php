<?php

namespace App\Contracts\Services\EventService;

use App\Models\Event;

interface EventServiceInterface
{
    public function store($data): Event;
    
    public function get($data);
    public function getUserEvents($data);
    public function getById(int $id): Event;
}
