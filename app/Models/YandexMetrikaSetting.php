<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YandexMetrikaSetting extends Model
{
    protected $fillable = [
        'counter_id',
        'enabled',
        'webvisor',
        'clickmap',
        'track_links',
        'accurate_track_bounce',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'webvisor' => 'boolean',
        'clickmap' => 'boolean',
        'track_links' => 'boolean',
        'accurate_track_bounce' => 'boolean',
    ];
}
