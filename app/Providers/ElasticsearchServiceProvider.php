<?php

namespace App\Providers;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Transport\NodePool\NodePoolInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\Client;

class ElasticsearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!config('elasticsearch.enabled'))
        {
            return;
        }
        $this->app->singleton(Client::class, function ($app) {
            $hosts = config('elasticsearch.hosts');
            return ClientBuilder::create()
                ->setBasicAuthentication(config('elasticsearch.auth.name'), config('elasticsearch.auth.password'))
                ->setHosts($hosts)
                ->build();
        });
    }

    public function boot()
    {
        //
    }
}
