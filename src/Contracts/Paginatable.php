<?php

namespace Merq\DataTable\Contracts;

use Merq\DataTable\SourceResult;

interface Paginatable
{
    /**
     * Retrieve a page of rows with its pagination metadata.
     */
    public function paginate(int $perPage, int $page = 1): SourceResult;
}
