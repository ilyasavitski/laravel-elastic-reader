<?php

namespace Merkeleon\ElasticReader\Providers;

use Illuminate\Support\ServiceProvider;

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
    }
}