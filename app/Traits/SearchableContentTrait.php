<?php

namespace App\Traits;

use App\Contracts\Services\ElasticsearchObserver;
use App\Models\Event;
use App\Models\Seance;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Carbon;

trait SearchableContentTrait
{
    public static function bootSearchable()
    {
        if (config('elasticsearch.enabled')) {
            static::observe(ElasticsearchObserver::class);
        }
    }
    public function getSearchIndex()
    {
        return $this->getTable();
    }
    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }
        return $this->getTable();
    }
    public function toSearchArray()
    {
        $object = $this->toArray();
        if (isset($object['date_start'])) {
            $object['date_start'] = Carbon::make($object['date_start']);
        }
        if (isset($object['date_end'])) {
            $object['date_end'] = Carbon::make($object['date_end']);
        }
        if(isset($object['latitude']) && isset($object['longitude'])) {
            $object['location'] = [
                "lat" => $object['latitude'],
                "lon" => $object['longitude']
            ];

            $seances = Seance::where('place_id', $object['id'])->get();
            foreach ($seances as $seance) {
                $object['seances'][] = [
                    'date_start' => Carbon::parse($seance->date_start),
                    'date_end' => Carbon::parse($seance->date_end),
                ];
            }
            $statuses = [];
            foreach (Event::find($object['event_id'])->statuses()->get() as $status) {
                $statuses[] = [
                    'name' => $status->name,
                    'last' => $status->pivot->last,
                ];
            }
            $object['status'] = $statuses;
            $object['types'] = Event::find($object['event_id'])->types()->get()->pluck('id');
        }
//        dd($object);
        return $object;
    }
}
