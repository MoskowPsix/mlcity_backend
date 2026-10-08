<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckpointParticipant extends Model
{
    protected $fillable = ['event_id', 'group_id', 'last_name', 'first_name', 'middle_name', 'birth_date', 'city', 'start_number', 'rfid'];
    protected $casts = ['birth_date' => 'date:Y-m-d'];

    public function group()
    {
        return $this->belongsTo(CheckpointGroup::class, 'group_id');
    }
}
