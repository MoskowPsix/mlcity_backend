<?php

namespace App\Contracts\Services;
use Elastic\Elasticsearch\Client;


class ElasticsearchObserver
{
    public function saved($model): void
    {
        if (config('elasticsearch.enabled')) {
            resolve(Client::class)->index([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
                'body' => $model->toSearchArray(),
            ]);
        }
    }
    public function deleted($model): void
    {
        if (config('elasticsearch.enabled')) {

            resolve(Client::class)->delete([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
            ]);
        }
    }
}
