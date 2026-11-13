<?php

namespace Merq\DataTable;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Inertia\PropertyContext;
use Inertia\ProvidesInertiaProperty;
use InvalidArgumentException;
use LogicException;
use Merq\DataTable\Contracts\Paginatable;
use Merq\DataTable\Contracts\Source;
use Merq\DataTable\Sources\CollectionSource;
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
     * Pagination preference, with null selecting the source capability.
     */
    protected ?bool $paginate = null;

    /**
     * Available numbers of rows per page.
     *
     * @var list<int>
     */
    protected array $perPageOptions = [25, 50, 100];

    /**
     * Default page size, falling back to the first available option.
     */
    protected ?int $defaultPerPage = null;

    /**
     * Prefix separating this table's query parameters from other tables.
     */
    protected ?string $queryPrefix = null;

    /**
     * Create a table instance with an optional data source.
     */
    public static function make(Source|Builder|Collection|array|null $source = null): static
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
    public function source(): Source|Builder|Collection|array|null
    {
        return null;
    }

    /**
     * Set the data source, adapting builders, collections and arrays.
     */
    public function setSource(Source|Builder|Collection|array $source): static
    {
        $this->source = match (true) {
            $source instanceof Source => $source,
            $source instanceof Builder => new EloquentSource($source),
            default => new CollectionSource($source),
        };

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
     * Retrieve the source result using the current pagination request.
     */
    public function getResult(?Request $request = null): SourceResult
    {
        $source = $this->getSource();

        if (! ($this->paginate ?? $source instanceof Paginatable)) {
            return $source->get();
        }

        if (! $source instanceof Paginatable) {
            throw new LogicException('The table source must implement Paginatable to enable pagination.');
        }

        $request ??= request();
        $defaultPerPage = $this->getDefaultPerPage();
        $perPage = $this->resolvePositiveInteger($request->query($this->qualifyQueryField('limit')));
        $page = $this->resolvePositiveInteger($request->query($this->qualifyQueryField('page'))) ?? 1;

        return $source->paginate(
            $perPage !== null && in_array($perPage, $this->getPerPageOptions(), true) ? $perPage : $defaultPerPage,
            $page,
        );
    }

    /**
     * Retrieve the current page or all items when pagination is disabled.
     *
     * @return Collection<array-key, mixed>
     */
    public function getItems(): Collection
    {
        return $this->getResult()->items;
    }

    /**
     * Enable, disable or automatically select source pagination.
     */
    public function paginate(?bool $paginate = true): static
    {
        $this->paginate = $paginate;

        return $this;
    }

    /**
     * Set the default number of rows per page.
     */
    public function defaultPerPage(?int $perPage = null): static
    {
        if ($perPage !== null && $perPage < 1) {
            throw new InvalidArgumentException('The default page size must be positive.');
        }

        $this->defaultPerPage = $perPage;

        return $this;
    }

    /**
     * Set allowed page sizes, or an empty list for a fixed page size.
     *
     * @param  list<int>  $options
     */
    public function perPageOptions(array $options): static
    {
        foreach ($options as $option) {
            if (! is_int($option) || $option < 1) {
                throw new InvalidArgumentException('Page size options must be positive integers.');
            }
        }

        $this->perPageOptions = array_values(array_unique($options));

        return $this;
    }

    /**
     * Retrieve the default page size.
     */
    public function getDefaultPerPage(): int
    {
        $perPage = $this->defaultPerPage ?? ($this->perPageOptions[0] ?? 25);

        if ($perPage < 1) {
            throw new InvalidArgumentException('The default page size must be positive.');
        }

        return $perPage;
    }

    /**
     * Retrieve allowed page sizes, including a custom default when selectable.
     *
     * @return list<int>
     */
    public function getPerPageOptions(): array
    {
        if ($this->perPageOptions === []) {
            return [];
        }

        $default = $this->getDefaultPerPage();

        return in_array($default, $this->perPageOptions, true)
            ? $this->perPageOptions
            : [$default, ...$this->perPageOptions];
    }

    /**
     * Set a prefix for query parameters used by the table.
     */
    public function withQueryPrefix(?string $prefix): static
    {
        $this->queryPrefix = $prefix;

        return $this;
    }

    /**
     * Qualify a query parameter used by this table.
     */
    protected function qualifyQueryField(string $field): string
    {
        return ($this->queryPrefix ?? '').$field;
    }

    /**
     * Resolve a positive integer without coercing malformed request values.
     */
    protected function resolvePositiveInteger(mixed $value): ?int
    {
        if (! is_int($value) && ! is_string($value)) {
            return null;
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return $integer === false ? null : $integer;
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
     * Build pagination state and navigation URLs for the frontend.
     */
    protected function renderPagination(?Pagination $pagination, Request $request): ?array
    {
        if ($pagination === null) {
            return null;
        }

        $pageName = $this->qualifyQueryField('page');
        $perPageName = $this->qualifyQueryField('limit');
        $url = fn (int $page): string => $request->fullUrlWithQuery([
            $pageName => $page,
            $perPageName => $pagination->perPage,
        ]);

        return [
            'currentPage' => $pagination->currentPage,
            'lastPage' => $pagination->lastPage,
            'perPage' => $pagination->perPage,
            'perPageOptions' => $this->getPerPageOptions(),
            'total' => $pagination->total,
            'from' => $pagination->total === 0 ? null : ($pagination->currentPage - 1) * $pagination->perPage + 1,
            'to' => $pagination->total === 0 ? null : min($pagination->currentPage * $pagination->perPage, $pagination->total),
            'pageName' => $pageName,
            'perPageName' => $perPageName,
            'firstPageUrl' => $pagination->currentPage > 1 ? $url(1) : null,
            'prevPageUrl' => $pagination->currentPage > 1 ? $url($pagination->currentPage - 1) : null,
            'nextPageUrl' => $pagination->currentPage < $pagination->lastPage ? $url($pagination->currentPage + 1) : null,
            'lastPageUrl' => $pagination->currentPage < $pagination->lastPage ? $url($pagination->lastPage) : null,
        ];
    }

    /**
     * Build the table when Inertia resolves the property.
     */
    public function toInertiaProperty(PropertyContext $context): array
    {
        $result = $this->getResult($context->request);

        return [
            'columns' => $this->getColumns()->all()
                ->map(fn (Column $column, int|string $id): array => $column->renderHeader((string) $id))
                ->values()
                ->all(),
            'rows' => $result->items
                ->map(fn (mixed $resource, int|string $key): array => $this->renderRow($resource, $key))
                ->values()
                ->all(),
            'pagination' => $this->renderPagination($result->pagination, $context->request),
        ];
    }
}
