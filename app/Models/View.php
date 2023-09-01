<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Sight;
use App\Models\User;

class View extends Model
{
    use HasFactory;
    protected $table = 'view';
    protected $fillable = [
        'user_id',
        'event_id',
        'sight_id',
        'time_view'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsToMany(Event::class, 'event_id');
    }

    public function sight(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sight::class, 'sight_id');
    }
}
