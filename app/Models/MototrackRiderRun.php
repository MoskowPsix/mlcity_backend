<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MototrackRiderRun extends Model
{
    protected $fillable = [
        'user_id',
        'sight_id',
        'device_id',
        'rfid_tag_number',
        'start_event_id',
        'finish_event_id',
        'started_at',
        'finished_at',
        'duration_ms',
        'start_payload',
        'finish_payload',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'duration_ms' => 'integer',
        'start_payload' => 'array',
        'finish_payload' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sight(): BelongsTo
    {
        return $this->belongsTo(Sight::class);
    }
}
