<?php

namespace Merq\DataTable\Sources;

use Illuminate\Support\Collection;
use Merq\DataTable\Contracts\Paginatable;
use Merq\DataTable\Contracts\Source;
use Merq\DataTable\Pagination;
use Merq\DataTable\SourceResult;

class CollectionSource implements Source, Paginatable
{
    /**
     * Items stored with their original source keys.
     *
     * @var Collection<array-key, mixed>
     */
    protected Collection $items;

    /**
     * Create a data source from an array or collection.
     *
     * @param  array<array-key, mixed>|Collection<array-key, mixed>  $items
     */
    public function __construct(array|Collection $items)
    {
        $this->items = new Collection($items);
    }

    /**
     * Retrieve all items without modifying the source collection.
     */
    public function get(): SourceResult
    {
        return new SourceResult(new Collection($this->items));
    }

    /**
     * Retrieve a page while preserving the original row keys.
     */
    public function paginate(int $perPage, int $page = 1): SourceResult
    {
        $pagination = new Pagination($page, $perPage, $this->items->count());

        return new SourceResult(
            $this->items->forPage($pagination->currentPage, $pagination->perPage),
            $pagination,
        );
    }
}
