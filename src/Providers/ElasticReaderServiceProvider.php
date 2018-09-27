<?php

namespace Merkeleon\ElasticReader\Providers;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Merkeleon\ElasticReader\Elastic\Elastic;

class ElasticReaderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/elastic_search.php' => config_path('elastic_search.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/elastic_search.php', 'elastic_search'
        );

        $this->app->bind(Elastic::class, function ($app) {
            return new Elastic(
                ClientBuilder::create()
                             ->setHosts(config('elastic_search.hosts'))
                             ->setLogger(ClientBuilder::defaultLogger(config('elastic_search.logPath')))
                             ->build()
            );
        });
    }
}