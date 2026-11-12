<?php

namespace Merq\DataTable\Tests\Fixtures\Tables;

use Illuminate\Database\Eloquent\Builder;
use Merq\DataTable\ColumnCollection;
use Merq\DataTable\Columns\Text;
use Merq\DataTable\Table;
use Merq\DataTable\Tests\Fixtures\Models\Customer;

class CustomerTable extends Table
{
    /**
     * Define the customer query.
     */
    public function source(): Builder
    {
        return Customer::query()->orderBy('id');
    }

    /**
     * Define the customer columns.
     */
    public function columns(): ColumnCollection
    {
        return ColumnCollection::of([
            Text::make('Name')->name('name'),
        ]);
    }
}
