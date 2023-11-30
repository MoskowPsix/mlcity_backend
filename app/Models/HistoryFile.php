<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryFile extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function historyFileType(){
        return $this->hasOne(HistoryFileType::class);
    }
    
}
