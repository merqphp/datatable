<?php

namespace Merq\DataTable;

use Illuminate\Support\Collection;

class SourceResult
{
    /**
     * Create a result from the retrieved items.
     *
     * @param  Collection<array-key, mixed>  $items
     */
    public function __construct(
        /**
         * Items retrieved from the data source.
         */
        public readonly Collection $items,
        /**
         * Pagination metadata when the source returned a page of items.
         */
        public readonly ?Pagination $pagination = null,
    ) {}
}
