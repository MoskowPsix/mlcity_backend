<?php

namespace App\Providers;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\Client;

class ElasticsearchServiceProvider extends ServiceProvider
{
    public function register():void
    {
        if (config('elasticsearch.enabled'))
        {
            $this->app->singleton(Client::class, function ($app) {
                $hosts = config('elasticsearch.hosts');
                return ClientBuilder::create()
                    ->setBasicAuthentication(config('elasticsearch.auth.name'), config('elasticsearch.auth.password'))
                    ->setHosts($hosts)
                    ->build();
            });
        }
    }

    public function boot()
    {
        //
    }
}
