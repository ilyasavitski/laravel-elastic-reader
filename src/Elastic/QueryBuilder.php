<?php


namespace Merkeleon\ElasticReader\Elastic;


class QueryBuilder
{
    protected $query = [];

    public function from(int $from)
    {
        $this->query['from'] = $from;

        return $this;
    }

    public function size(int $size)
    {
        $this->query['size'] = $size;

        return $this;
    }

    public function sort($sort)
    {
        $this->query['sort'] = $sort;

        return $this;
    }

    public function build()
    {
        $build = [
            'from' => array_get($this->query, 'from', 0),
            'size' => array_get($this->query, 'size', 50)
        ];

        if ($body = array_get($this->query, 'body'))
        {
            $build['body'] = $body;
        }

        return $build;
    }

    protected function merge(array $query, $mode = 'filter')
    {
        $this->query['body']['query']['bool'][$mode][] = $query;

        return $this;
    }

    public function where($field, $value)
    {
        $query = ['term' => [$field => $value]];

        $this->merge($query);

        return $this;
    }

    public function range($field, $start = null, $end = null)
    {
        $query = [];

        if ($start)
        {
            $query['range'][$field]['gte'] = $start;
        }

        if ($end)
        {
            $query['range'][$field]['lte'] = $end;
        }

        if ($query)
        {
            $this->merge($query);
        }

        return $this;
    }

    public function matchSubString($field, $value)
    {
        $query = ['query_string' => ['default_field' => $field, "query" => '*' . $value . '*']];

        $this->merge($query, 'must');

        return $this;
    }
}