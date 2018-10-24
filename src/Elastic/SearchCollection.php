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

    public static function createFromElasticResponse($elasticResponse = [], callable $callbackHit = null, array $callbacks = null)
    {
        if (!$callbackHit)
        {
            $items = $elasticResponse['hits']['hits'];
        }
        else
        {
            $items = array_map(
                function ($item) use ($callbackHit) {
                    return $callbackHit($item);
                },
                $elasticResponse['hits']['hits']
            );
        }

        if ($callbacks)
        {
            foreach ($callbacks as $callback)
            {
                if (is_callable($callback))
                {
                    $items = $callback($items);
                }
            }
        }

        return (new static($items))
            ->setTook($elasticResponse['took'])
            ->setTimedOut($elasticResponse['timed_out'])
            ->setTotal($elasticResponse['hits']['total'])
            ->setMaxScore($elasticResponse['hits']['max_score'])
            ->setShards($elasticResponse['_shards']);
    }

    public function setTook(int $took)
    {
        $this->took = $took;

        return $this;
    }

    /**
     * @return int
     */
    public function getTook()
    {
        return $this->took;
    }

    public function setTimedOut(bool $timeOut)
    {
        $this->isTimedOut = $timeOut;

        return $this;
    }


    /**
     * @return bool
     */
    public function isTimedOut()
    {
        return $this->isTimedOut;
    }


    public function setTotal(int $total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }


    public function setMaxScore(int $maxScore = null)
    {
        $this->maxScore = $maxScore;

        return $this;
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

    public function setShards(array $shards)
    {
        $this->shards = $shards;

        return $this;
    }

    public function getShards()
    {
        return $this->shards;
    }
}
