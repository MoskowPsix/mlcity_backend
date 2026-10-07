<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CheckpointHeat extends Model {
    protected $fillable = ['event_id','external_id','name','status','started_at','finished_at'];
    protected $casts = ['started_at' => 'datetime', 'finished_at' => 'datetime'];
    public function results() { return $this->hasMany(CheckpointResult::class, 'heat_id'); }
}
