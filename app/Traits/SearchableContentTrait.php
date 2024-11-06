<?php

namespace App\Traits;

use App\Contracts\Services\ElasticsearchObserver;
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
        return $object;
    }
}
