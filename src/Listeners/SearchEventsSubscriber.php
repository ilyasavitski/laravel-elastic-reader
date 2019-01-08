<?php

namespace Merkeleon\ElasticReader\Listeners;

use Merkeleon\ElasticReader\Events\BeforeSearchInElasticSearchEvent;

class SearchEventsSubscriber
{
    public function onBeforeElasticSearch(BeforeSearchInElasticSearchEvent $event)
    {
        if (config('elastic_search.log_search_params'))
        {
            logger()->info('Search in ElasticSearch with params', $event->getParams());
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'Merkeleon\ElasticReader\Events\BeforeSearchInElasticSearchEvent',
            'Merkeleon\ElasticReader\Listeners\SearchEventsSubscriber@onBeforeElasticSearch'
        );
    }
}