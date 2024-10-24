<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPrice extends Model
{
    use HasFactory;

    protected $table = "history_prices";

    protected $guarded = false;

    public function price(){
        return $this->belongsTo(Price::class);
    }

    public function historyContent(){
        return $this->belongsTo(HistoryContent::class);
    }
}
