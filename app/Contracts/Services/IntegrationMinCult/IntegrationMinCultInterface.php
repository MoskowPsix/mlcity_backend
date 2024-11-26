<?php

namespace App\Contracts\Services\IntegrationMinCult;

interface IntegrationMinCultInterface
{
    public function getTotal(): int;
    public function getEvents(int $limit, int $offset): array;
}
