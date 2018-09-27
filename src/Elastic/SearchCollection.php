<?php


namespace Merkeleon\ElasticReader\Elastic;

use Illuminate\Support\Collection;

class SearchCollection extends Collection
{
    protected $took;
    protected $isTimedOut;
    protected $total;
    protected $maxScore;
    protected $shards;

    public function __construct($elasticResponse = [], callable $callback = null)
    {
        if (!$callback)
        {
            $this->items = $elasticResponse['hits']['hits'];
        }
        else
        {
            $this->items = array_map(
                function ($item) use ($callback) {
                    return $callback($item);
                },
                $elasticResponse['hits']['hits']
            );
        }

        $this->took       = $elasticResponse['took'];
        $this->isTimedOut = $elasticResponse['timed_out'];
        $this->total      = $elasticResponse['hits']['total'];
        $this->maxScore   = $elasticResponse['hits']['max_score'];
        $this->shards     = $elasticResponse['_shards'];
    }

    /**
     * @return int
     */
    public function getTook()
    {
        return $this->took;
    }

    /**
     * @return bool
     */
    public function isTimedOut()
    {
        return $this->isTimedOut;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getMaxScore()
    {
        return $this->maxScore;
    }

    /**
     * @return array
     */
    public function getShards()
    {
        return $this->shards;
    }
}
