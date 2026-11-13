<?php

namespace Merq\DataTable;

use InvalidArgumentException;

class Pagination
{
    /**
     * Current page within the available range.
     */
    public readonly int $currentPage;

    /**
     * Last available page, including an empty first page.
     */
    public readonly int $lastPage;

    /**
     * Build pagination metadata from a requested page and item count.
     */
    public function __construct(
        int $currentPage,
        /**
         * Number of items on each page.
         */
        public readonly int $perPage,
        /**
         * Total number of items in the source.
         */
        public readonly int $total,
    ) {
        if ($perPage < 1 || $total < 0) {
            throw new InvalidArgumentException('Pagination requires a positive page size and a non-negative total.');
        }

        $this->lastPage = max(1, (int) ceil($total / $perPage));
        $this->currentPage = min(max(1, $currentPage), $this->lastPage);
    }
}
