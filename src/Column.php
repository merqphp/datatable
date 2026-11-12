<?php

namespace Merq\DataTable;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;

abstract class Column
{
    use Conditionable;

    /**
     * Custom name identifying the column.
     */
    protected ?string $name = null;

    /**
     * Create a new column.
     */
    public function __construct(
        /**
         * The column title.
         */
        protected string $title,
        /**
         * Attribute path or callback used to resolve the cell value.
         */
        protected string|Closure|null $attribute = null,
    ) {}

    /**
     * Create a new column instance.
     */
    public static function make(string $title, string|Closure|null $attribute = null): static
    {
        return new static($title, $attribute);
    }

    /**
     * Set the column title.
     */
    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retrieve the column title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set a custom name for the column.
     */
    public function name(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retrieve the custom column name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Retrieve the name of the component used to render a cell.
     */
    abstract public function component(): string;

    /**
     * Build the component props for a cell.
     */
    abstract public function toView(mixed $value, mixed $resource): array;

    /**
     * Resolve the attribute or callback, falling back to the title in snake case.
     */
    protected function getAttribute(): string|Closure
    {
        return $this->attribute ?? Str::snake($this->title);
    }

    /**
     * Resolve the cell value from a resource.
     */
    public function resolveValue(mixed $resource): mixed
    {
        $attribute = $this->getAttribute();

        return $attribute instanceof Closure
            ? $attribute($resource)
            : data_get($resource, $attribute);
    }

    /**
     * Build the column definition for the frontend.
     */
    public function renderHeader(string $id): array
    {
        return [
            'id' => $id,
            'title' => $this->getTitle(),
        ];
    }

    /**
     * Build a cell value for the given resource.
     */
    public function renderCell(string $id, mixed $resource): array
    {
        return [
            'column' => $id,
            'component' => $this->component(),
            'props' => (object) $this->toView($this->resolveValue($resource), $resource),
        ];
    }
}
