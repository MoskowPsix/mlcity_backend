<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $table = 'places';
    protected $fillable = [
        'event_id',
        'sight_id',
        'location_id',
        'latitude',
        'longitude',
        'address'
    ];

    public function sight () {
        $this->belongsTo(Sight::class, 'sight_id');
    }
    public function event () {
        $this->belongsTo(Sight::class, 'event_id');
    }
}
