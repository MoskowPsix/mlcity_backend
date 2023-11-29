<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HistoryContent extends Model
{
    use HasFactory;

    protected $table = "history_contents";

    protected $guarded = false;

    public function historyContentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function historyPlaces(){
        return $this->hasMany(HistoryPlace::class);
    }

    public function historyPrices(){
        return $this->hasMany(HistoryPrice::class);
    }

    public function historyContentStatuses(){
        return $this->hasMany(HistoryContentStatuses::class);
    }

    public function historyFiles(){
        return $this->hasMany(HistoryFile::class);
    }

}
