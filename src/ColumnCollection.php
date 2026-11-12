<?php

namespace Merq\DataTable;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;

class ColumnCollection
{
    use Conditionable;

    /**
     * Columns keyed by their resolved names.
     *
     * @var array<string, Column>
     */
    protected array $columns = [];

    /**
     * Create a column collection from an array or Laravel collection.
     *
     * @param  array<array-key, Column|null>|Collection<array-key, Column|null>  $columns
     */
    public static function of(array|Collection $columns = []): static
    {
        $collection = new static;

        foreach ($columns as $column) {
            if ($column !== null) {
                $collection->add($column);
            }
        }

        return $collection;
    }

    /**
     * Add a column or replace one with the same name.
     */
    public function add(Column $column): static
    {
        $this->columns[$this->resolveColumnName($column)] = $column;

        return $this;
    }

    /**
     * Add or move a column to the end of the collection.
     */
    public function append(Column $column): static
    {
        $name = $this->resolveColumnName($column);

        unset($this->columns[$name]);

        $this->columns[$name] = $column;

        return $this;
    }

    /**
     * Add or move a column to the beginning of the collection.
     */
    public function prepend(Column $column): static
    {
        $name = $this->resolveColumnName($column);

        $this->columns = [$name => $column] + $this->columns;

        return $this;
    }

    /**
     * Remove a column by its resolved name.
     */
    public function remove(string $name): static
    {
        unset($this->columns[$name]);

        return $this;
    }

    /**
     * Insert a column after another, or append it when the target is missing.
     */
    public function after(string $name, Column $column): static
    {
        if (! array_key_exists($name, $this->columns)) {
            return $this->append($column);
        }

        $columnName = $this->resolveColumnName($column);

        if ($columnName === $name) {
            $this->columns[$name] = $column;

            return $this;
        }

        $columns = [];

        foreach ($this->columns as $key => $existing) {
            if ((string) $key === $columnName) {
                continue;
            }

            $columns[$key] = $existing;

            if ((string) $key === $name) {
                $columns[$columnName] = $column;
            }
        }

        $this->columns = $columns;

        return $this;
    }

    /**
     * Retrieve the custom name or generate an unused column name.
     */
    protected function resolveColumnName(Column $column): string
    {
        if ($name = $column->getName()) {
            return $name;
        }

        $position = count($this->columns) + 1;

        do {
            $name = 'col-'.str_pad((string) $position++, 2, '0', STR_PAD_LEFT).'-'.Str::of($column->getTitle())->ascii()->kebab();
        } while (array_key_exists($name, $this->columns));

        return $name;
    }

    /**
     * Retrieve all columns keyed by their resolved names.
     *
     * @return Collection<string, Column>
     */
    public function all(): Collection
    {
        return new Collection($this->columns);
    }
}
