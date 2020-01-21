<?php

namespace Merkeleon\ElasticReader\Providers;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Merkeleon\ElasticReader\Elastic\Elastic;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

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

        $this->app->register(EventServiceProvider::class);

        $this->app->bind(Elastic::class, function ($app) {
            $logger = new Logger('log');
            $logger->pushHandler(new StreamHandler(config('elastic_search.logPath'), Logger::WARNING));

            return new Elastic(
                ClientBuilder::create()
                             ->setHosts(config('elastic_search.hosts'))
                             ->setLogger($logger)
                             ->build()
            );
        });
    }
}