<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class EventType extends Model
{
    use HasFactory;

    protected $table = 'etypes';

    protected $fillable = [
        'name',
        'ico',
    ];

    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }
}
