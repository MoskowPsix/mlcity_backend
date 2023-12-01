<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SightType extends Model
{
    use HasFactory;

    protected $table = 'stypes';

    protected $fillable = [
        'name',
        'ico',
        'cult_id',
        'stype_id'
    ];

    public function historyContents(){
        return $this->morphToMany(HistoryContent::class, "history_contentable");
    }

    public function sights(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Sight::class,'sights_stypes','stype_id','sight_id');
    }

    public function stypes(): HasMany
    {
        return $this->hasMany(SightType::class, 'stype_id')->with('stypes');
    }
}
