<?php

namespace App\Models;

use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Model;

class ElasticsearchModel extends Model
{


    public static function boot(): void
    {
        parent::boot();
        self::saved(function ($model) {
            if (config('elasticsearch.enabled'))
            {
                if($model->getSearchType() == "places" && !resolve(Client::class)->indices()->exists(['index' => $model->getSearchIndex()])) {
                    $params = [
                        'index' => $model->getSearchIndex(),
                        'body' => [
                            'mappings' => [
                                'properties' => [
                                    'location' => [
                                        'type' => 'geo_point'
                                    ]
                                ]
                            ]
                        ]
                    ];
                    resolve(Client::class)->indices()->create($params);
                }
                $body = $model->toSearchArray();
//                $body['files'] = $model->files()->get()->toArray();
                resolve(Client::class)->index([
                    'index' => $model->getSearchIndex(),
                    'type' => $model->getSearchType(),
                    'id' => $model->getKey(),
                    'body' => $body,
                ]);
            }
        });
        self::deleted(function ($model) {
            if (!config('elasticsearch.enabled'))
            {
                return;
            } else {
                resolve(Client::class)->delete([
                    'index' => $model->getSearchIndex(),
                    'type' => $model->getSearchType(),
                    'id' => $model->getKey(),
                ]);
            }
        });
    }
}
