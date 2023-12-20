<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorySeance extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function historyPlace(){
        return $this->belongsTo(HistoryPlace::class);
    }

    public function seance(){
        return $this->belongsTo(Seance::class);
    }
}
