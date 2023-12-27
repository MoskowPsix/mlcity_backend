<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPlace extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function place(){
        return $this->belongsTo(Place::class);
    }
    public function location() {
        return $this->belongsTo(Location::class, 'location_id')->with('locationParent');
    }

    public function historyContent(){
        return $this->belongsTo(HistoryContent::class);
    }

    public function historySeances(){
        return $this->hasMany(HistorySeance::class);
    }
}
