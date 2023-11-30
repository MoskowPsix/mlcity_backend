<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    protected $table = 'seances';
    protected $fillable = [
        'place_id',
        'dateStart',
        'dateEnd',
    ];

    public function historySeances(){
        return $this->belongsTo(HistorySeance::class);
    }
}
