<?php

namespace App\Tables\Columns;

use Merq\DataTable\Column;

class Email extends Column
{
    /**
     * Retrieve the name of the playground's email component.
     */
    public function component(): string
    {
        return 'App::Columns/Email';
    }

    /**
     * Build the email link props.
     */
    public function toView(mixed $value, mixed $resource): array
    {
        return [
            'label' => $value,
            'href' => 'mailto:'.$value,
        ];
    }
}
