<?php

namespace Merq\DataTable\Sources;

use Illuminate\Database\Eloquent\Builder;
use Merq\DataTable\Contracts\Source;
use Merq\DataTable\SourceResult;

class EloquentSource implements Source
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
}
