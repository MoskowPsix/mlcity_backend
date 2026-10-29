<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CheckpointResult extends Model {
    protected $fillable = [
        'heat_id',
        'participant_id',
        'place',
        'laps',
        'result',
        'result_value_ms',
        'best_lap_time_ms',
        'last_lap_time_ms',
        'lap_times',
    ];
    protected $casts = [
        'lap_times' => 'array',
        'result_value_ms' => 'integer',
        'best_lap_time_ms' => 'integer',
        'last_lap_time_ms' => 'integer',
        'laps' => 'integer',
        'place' => 'integer',
    ];
    public function participant() { return $this->belongsTo(CheckpointParticipant::class, 'participant_id'); }
}
