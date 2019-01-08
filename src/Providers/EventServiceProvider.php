<?php


namespace Merkeleon\ElasticReader\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Merkeleon\ElasticReader\Listeners\SearchEventsSubscriber;


class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        SearchEventsSubscriber::class,
    ];

    public function boot()
    {
        parent::boot();
    }
}