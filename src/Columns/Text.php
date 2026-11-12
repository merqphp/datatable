<?php

namespace Merq\DataTable\Columns;

use Merq\DataTable\Column;

class Text extends Column
{
    /**
     * Retrieve the name of the component used to render text.
     */
    public function component(): string
    {
        return 'DataTable::Columns/Text';
    }

    /**
     * Build the text component props.
     */
    public function toView(mixed $value, mixed $resource): array
    {
        return ['value' => $value];
    }
}
