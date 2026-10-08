<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CheckpointResult extends Model {
    protected $fillable = ['heat_id','participant_id','place','result','result_value_ms'];
    public function participant() { return $this->belongsTo(CheckpointParticipant::class, 'participant_id'); }
}
