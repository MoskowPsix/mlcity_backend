<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStatusContent extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function historyContent(){
        return $this->belongsTo(HistoryContent::class);
    }
}
