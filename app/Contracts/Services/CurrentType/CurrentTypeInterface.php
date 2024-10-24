<?php

namespace App\Contracts\Services\CurrentType;

interface CurrentTypeInterface
{
    public function getType(): array | null;
}
