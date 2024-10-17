<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Status;
use App\Models\User;
use MoonShine\Fields\Relationships\BelongsToMany;

class HistoryContent extends Model
{
    use HasFactory;

    protected $table = "history_contents";

    protected $guarded = false;

    public function historyEventTypes(){
        return $this->morphedByMany(EventType::class, "history_contentable")->withPivot("on_delete");

    }
    public function historySightTypes(){
        return $this->morphedByMany(SightType::class, "history_contentable")->withPivot("on_delete");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function statuses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Status::class, 'history_content_statuses')->withTimestamps();
    }

    public function historyContentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function historyPlaces(){
        return $this->hasMany(HistoryPlace::class)->with('historySeances', 'location', 'historySeances');
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
