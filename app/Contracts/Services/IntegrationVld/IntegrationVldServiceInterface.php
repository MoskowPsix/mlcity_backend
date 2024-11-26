<?php

namespace App\Contracts\Services\IntegrationVld;

interface IntegrationVldServiceInterface
{
    public function getFirstScroll(): object | null;
    public function getNextScroll(string $scroll_id): object | null;
}
