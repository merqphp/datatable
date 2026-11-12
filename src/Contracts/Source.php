<?php

namespace Merq\DataTable\Contracts;

use Merq\DataTable\SourceResult;

interface Source
{
    /**
     * Retrieve items from the data source.
     */
    public function get(): SourceResult;
}
