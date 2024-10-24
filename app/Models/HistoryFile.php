<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryFile extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function historyFileType(){
        return $this->belongsToMany(FileType::class,"history_file_type","history_file_id","type_id");
    }
    
}
