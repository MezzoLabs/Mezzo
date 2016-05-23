<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories;


use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use MezzoLabs\Mezzo\Core\Schema\Relations\Scopes;
use MezzoLabs\Mezzo\Http\Requests\Queries\Filter;
use MezzoLabs\Mezzo\Http\Requests\Queries\Filters;
use MezzoLabs\Mezzo\Http\Requests\Queries\Pagination;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;
use MezzoLabs\Mezzo\Http\Requests\Queries\SearchQuery;
use MezzoLabs\Mezzo\Http\Requests\Queries\Sorting;
use MezzoLabs\Mezzo\Http\Requests\Queries\Sortings;

class EloquentQueryExecutor implements QueryExecutorContract
{
    /**
     * @var EloquentBuilder
     */
    protected $eloquentBuilder;

    /**
     * @var QueryObject
     */
    protected $queryObject;

    /**
     * EloquentSearcher constructor.
     * @param QueryObject $queryObject
     * @param EloquentBuilder $eloquentBuilder
     */
    public function __construct(QueryObject $queryObject, EloquentBuilder $eloquentBuilder)
    {
        $this->eloquentBuilder = $eloquentBuilder;
        $this->queryObject = $queryObject;
    }

    public function run() : EloquentBuilder
    {
        $this->applyScopes($this->queryObject->scopes());
        $this->applyPagination($this->queryObject->pagination());
        $this->applySearchQuery($this->queryObject->searchQuery());
        $this->applyFilters($this->queryObject->filters());
        $this->applySortings($this->queryObject->sortings());

        return $this->eloquentBuilder;
    }

    protected function applySearchQuery(SearchQuery $searchQuery)
    {
        if ($searchQuery->isEmpty())
            return;

        //TODO: Improve this please


        $this->eloquentBuilder->where(function ($subQuery) use ($searchQuery) {
            foreach ($searchQuery->columns() as $column) {
                $subQuery->orWhere($column, 'LIKE', '%' . $searchQuery->value() . '%');
            }
        });



    }

    protected function applyScopes(Scopes $scopes)
    {
        if ($scopes->isEmpty())
            return;

        $scopes->addToQuery($this->eloquentBuilder);

    }

    protected function applyFilters(Filters $filters)
    {
        $filters->each(function (Filter $filter) {
            $this->eloquentBuilder->where($filter->column(), '=', $filter->value());
        });
    }

    protected function applySortings(Sortings $sortings)
    {
        $sortings->each(function (Sorting $sorting) {
            $this->eloquentBuilder->orderBy($sorting->by(), $sorting->mysqlDirection());
        });
    }

    protected function applyPagination(Pagination $pagination)
    {
        if ($pagination->isEmpty())
            return;

        $this->eloquentBuilder
            ->skip($pagination->offset())
            ->take($pagination->limit());
    }


    public function setQueryObject(QueryObject $queryObject) : QueryObject
    {
        $this->queryObject = $queryObject;
    }

    public function getQueryObject() : QueryObject
    {
        return $this->queryObject;
    }


}