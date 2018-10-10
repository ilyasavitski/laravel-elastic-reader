<?php


namespace Merkeleon\ElasticReader\Elastic;


use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class SearchModel
{
    protected static $index;
    protected static $type;

    protected $queryBuilder;

    public static function search(QueryBuilder $builder = null)
    {
        $elastic = app(Elastic::class);

        $builderParameters = $builder ? $builder->build() : [];

        $parameters = array_merge(
            [
                'index' => static::$index,
                'type'  => static::$type,
            ],
            $builderParameters
        );

        $elasticResponse = $elastic->search($parameters);

        return new SearchCollection($elasticResponse, static::class . '::prepareHit');
    }

    public function query(QueryBuilder $queryBuilder = null)
    {
        if ($queryBuilder)
        {
            $this->queryBuilder = $queryBuilder;
        }
        elseif (!$this->queryBuilder)
        {
            $this->queryBuilder = new QueryBuilder();
        }

        return $this->queryBuilder;
    }

    public function paginate($perPage)
    {
        $page = Paginator::resolveCurrentPage();

        $offSet = max(0, ($page - 1) * $perPage);

        $this->query()
             ->from($offSet)
             ->size($perPage);

        $results = static::search($this->query());

        return $this->paginator($results, $results->getTotal(), $perPage, $page, [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()
                        ->makeWith(LengthAwarePaginator::class, compact(
                            'items', 'total', 'perPage', 'currentPage', 'options'
                        ));
    }

    public static function prepareHit($hit)
    {
        return $hit;
    }
}