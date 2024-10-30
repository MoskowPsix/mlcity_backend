<?php

namespace App\Models;

use App\Models\Event;
use App\Traits\SearchableContentTrait;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EventFile extends Model
{
    use SearchableContentTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'local'
    ];

    public static function boot(): void
    {
        parent::boot();
        self::saved(function (EventFile $model) {
            if(config('elasticsearch.enabled')) {
                $event = Event::with('files')->find($model->event_id)->toArray();
                resolve(Client::class)->index([
                    'index' => 'events',
                    'type' => $model->getSearchType(),
                    'id' => $model->event_id,
                    'body' => $event,
                ]);
            }
        });
        self::deleted(function (EventFile $model) {
            if(config('elasticsearch.enabled')) {

                $event = Event::with('files')->find($model->event_id)->toArray();
                resolve(Client::class)->index([
                    'index' => 'events',
                    'type' => $model->getSearchType(),
                    'id' => $model->event_id,
                    'body' => $event,
                ]);
            }
        });
    }

    public function event(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function file_types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FileType::class, 'event_file_types','file_id','type_id');

    }
}
