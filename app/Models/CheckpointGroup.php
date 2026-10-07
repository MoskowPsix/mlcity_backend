<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckpointGroup extends Model
{
    protected $fillable = ['event_id', 'name', 'sort_order'];

    public function participants()
    {
        return $this->hasMany(CheckpointParticipant::class, 'group_id');
    }
}
