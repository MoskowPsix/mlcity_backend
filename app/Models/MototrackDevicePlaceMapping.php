<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MototrackDevicePlaceMapping extends Model
{
    protected $fillable = [
        'device_id',
        'sight_id',
        'name',
        'notes',
    ];

    public function sight(): BelongsTo
    {
        return $this->belongsTo(Sight::class);
    }

    public function setDeviceIdAttribute(mixed $value): void
    {
        $this->attributes['device_id'] = trim((string) $value);
    }
}
