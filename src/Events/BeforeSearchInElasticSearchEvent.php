<?php

namespace Merkeleon\ElasticReader\Events;

class BeforeSearchInElasticSearchEvent
{
    /**
     * @var array
     */
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
