<?php

namespace Merq\DataTable\Columns;

use Merq\DataTable\Column;

class Text extends Column
{
    /**
     * Retrieve the column type.
     */
    public function getType(): string
    {
        return 'text';
    }
}
