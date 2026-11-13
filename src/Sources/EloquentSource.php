<?php

namespace Merq\DataTable\Sources;

use Illuminate\Database\Eloquent\Builder;
use Merq\DataTable\Contracts\Paginatable;
use Merq\DataTable\Contracts\Source;
use Merq\DataTable\Pagination;
use Merq\DataTable\SourceResult;

class EloquentSource implements Source, Paginatable
{
    /**
     * Create a data source from an Eloquent builder.
     */
    public function __construct(
        /**
         * Query builder used to retrieve the items.
         */
        protected Builder $builder,
    ) {}

    /**
     * Retrieve the underlying Eloquent builder.
     */
    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * Retrieve items using a clone of the query builder.
     */
    public function get(): SourceResult
    {
        return new SourceResult((clone $this->builder)->get());
    }

    /**
     * Retrieve a page without modifying the original query builder.
     */
    public function paginate(int $perPage, int $page = 1): SourceResult
    {
        $builder = clone $this->builder;
        $pagination = new Pagination($page, $perPage, $builder->toBase()->getCountForPagination());

        $paginator = $builder->paginate(
            perPage: $pagination->perPage,
            page: $pagination->currentPage,
            total: $pagination->total,
        );

        return new SourceResult($paginator->getCollection(), $pagination);
    }
}
