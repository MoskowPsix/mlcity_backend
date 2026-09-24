<?php

namespace App\Http\Resources\Mototrack;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MototrackRiderRunResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sight_id' => $this->sight_id,
            'sight_name' => $this->sightDisplayName(),
            'place_id' => $this->sight_id,
            'place_name' => $this->sightDisplayName(),
            'device_id' => $this->device_id,
            'rfid_tag_number' => $this->rfid_tag_number,
            'started_at' => $this->started_at?->toISOString(),
            'finished_at' => $this->finished_at?->toISOString(),
            'duration_ms' => $this->duration_ms,
            'status' => $this->finished_at === null ? 'active' : 'finished',
        ];
    }

    private function sightDisplayName(): string
    {
        $name = trim((string) ($this->sight?->name ?? ''));

        if ($name !== '') {
            return $name;
        }

        return 'Место #'.$this->sight_id;
    }
}
