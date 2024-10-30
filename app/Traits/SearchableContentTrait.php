<?php

namespace App\Traits;

use App\Contracts\Services\ElasticsearchObserver;
use Elastic\Elasticsearch\Client;

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
        return $this->toArray();
    }
}
