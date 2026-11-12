<?php

namespace Merq\DataTable;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Inertia\PropertyContext;
use Inertia\ProvidesInertiaProperty;
use LogicException;
use Merq\DataTable\Contracts\Source;
use Merq\DataTable\Sources\EloquentSource;

class Table implements ProvidesInertiaProperty
{
    use Conditionable;

    /**
     * Resolved source of table data.
     */
    protected ?Source $source = null;

    /**
     * Resolved collection of table columns.
     */
    protected ?ColumnCollection $columns = null;

    /**
     * Create a table instance with an optional data source.
     */
    public static function make(Source|Builder|null $source = null): static
    {
        $table = app(static::class);

        if ($source !== null) {
            $table->setSource($source);
        }

        return $table;
    }

    /**
     * Define the default data source for the table.
     */
    public function source(): Source|Builder|null
    {
        return null;
    }

    /**
     * Set the data source, adapting Eloquent builders automatically.
     */
    public function setSource(Source|Builder $source): static
    {
        $this->source = $source instanceof Source ? $source : new EloquentSource($source);

        return $this;
    }

    /**
     * Retrieve the data source, resolving the defined source when needed.
     */
    public function getSource(): Source
    {
        if ($this->source === null) {
            $source = $this->source();

            if ($source === null) {
                throw new LogicException('A table source must be provided through make(), setSource(), or source().');
            }

            $this->setSource($source);
        }

        return $this->source;
    }

    /**
     * Retrieve items from the table source.
     *
     * @return Collection<array-key, mixed>
     */
    public function getItems(): Collection
    {
        return $this->getSource()->get()->items;
    }

    /**
     * Define the table columns.
     *
     * @return array<array-key, Column|null>|Collection<array-key, Column|null>|ColumnCollection
     */
    public function columns(): array|Collection|ColumnCollection
    {
        return [];
    }

    /**
     * Retrieve the columns, resolving the definition when needed.
     */
    public function getColumns(): ColumnCollection
    {
        if ($this->columns === null) {
            $columns = $this->columns();

            $this->columns = $columns instanceof ColumnCollection ? $columns : ColumnCollection::of($columns);
        }

        return $this->columns;
    }

    /**
     * Add a column to the table.
     */
    public function column(Column $column): static
    {
        $this->getColumns()->add($column);

        return $this;
    }

    /**
     * Append columns or modify the current collection using a callback.
     *
     * @param  array<array-key, Column|null>|Collection<array-key, Column|null>|ColumnCollection|Closure(ColumnCollection): (ColumnCollection|Collection|array|null)  $columns
     */
    public function withColumns(array|Collection|ColumnCollection|Closure $columns): static
    {
        if ($columns instanceof Closure) {
            $current = $this->getColumns();
            $result = $columns($current);

            if ($result !== null) {
                $this->columns = $result instanceof ColumnCollection ? $result : ColumnCollection::of($result);
            }

            return $this;
        }

        foreach ($columns instanceof ColumnCollection ? $columns->all() : $columns as $column) {
            if ($column !== null) {
                $this->column($column);
            }
        }

        return $this;
    }

    /**
     * Build a row from its resource and source key.
     */
    protected function renderRow(mixed $resource, int|string $key): array
    {
        return [
            'key' => $resource instanceof Model ? ($resource->getKey() ?? $key) : $key,
            'cells' => $this->getColumns()->all()
                ->map(fn (Column $column, int|string $id): array => $column->renderCell((string) $id, $resource))
                ->values()
                ->all(),
        ];
    }

    /**
     * Build the table when Inertia resolves the property.
     */
    public function toInertiaProperty(PropertyContext $context): array
    {
        return [
            'columns' => $this->getColumns()->all()
                ->map(fn (Column $column, int|string $id): array => $column->renderHeader((string) $id))
                ->values()
                ->all(),
            'rows' => $this->getItems()
                ->map(fn (mixed $resource, int|string $key): array => $this->renderRow($resource, $key))
                ->values()
                ->all(),
        ];
    }
}
