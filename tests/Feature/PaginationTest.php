<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Merq\DataTable\Columns\Text;
use Merq\DataTable\Contracts\Source;
use Merq\DataTable\SourceResult;
use Merq\DataTable\Sources\CollectionSource;
use Merq\DataTable\Sources\EloquentSource;
use Merq\DataTable\Table;
use Merq\DataTable\Tests\Fixtures\Models\Customer;

beforeEach(function () {
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
    });

    Customer::query()->insert(array_map(
        fn (int $id): array => ['id' => $id, 'name' => "Customer {$id}"],
        range(1, 60),
    ));
});

it('paginates Eloquent by default only when Inertia resolves the property', function () {
    $query = Customer::query()->where('id', '>', 5)->orderBy('id');
    $table = Table::make($query)->withColumns([Text::make('Name')->name('name')]);

    DB::enableQueryLog();
    $response = Inertia::render('Customers', ['customers' => $table]);
    expect(DB::getQueryLog())->toBeEmpty();

    Route::get('/customers', fn () => $response);

    $response = $this->get('/customers?page=2&search=kept', ['X-Inertia' => 'true'])
        ->assertOk()
        ->assertJsonCount(25, 'props.customers.rows')
        ->assertJsonPath('props.customers.rows.0.key', 31)
        ->assertJsonPath('props.customers.rows.24.key', 55)
        ->assertJsonPath('props.customers.pagination', [
            'currentPage' => 2,
            'lastPage' => 3,
            'perPage' => 25,
            'perPageOptions' => [25, 50, 100],
            'total' => 55,
            'from' => 26,
            'to' => 50,
            'pageName' => 'page',
            'perPageName' => 'limit',
            'firstPageUrl' => 'http://localhost/customers?page=1&search=kept&limit=25',
            'prevPageUrl' => 'http://localhost/customers?page=1&search=kept&limit=25',
            'nextPageUrl' => 'http://localhost/customers?page=3&search=kept&limit=25',
            'lastPageUrl' => 'http://localhost/customers?page=3&search=kept&limit=25',
        ]);

    expect(DB::getQueryLog())->toHaveCount(2)
        ->and($query->getQuery()->limit)->toBeNull()
        ->and($query->getQuery()->offset)->toBeNull();
});

it('adapts arrays and collections while rendering only the current page', function (bool $useCollection) {
    $items = [
        'ada' => ['name' => 'Ada'],
        'grace' => ['name' => 'Grace'],
        'margaret' => ['name' => 'Margaret'],
        'barbara' => ['name' => 'Barbara'],
        'frances' => ['name' => 'Frances'],
    ];
    $rendered = [];
    $source = $useCollection ? collect($items) : $items;
    $table = Table::make($source)->perPageOptions([2, 5])->withColumns([
        Text::make('Name', function (array $row) use (&$rendered): string {
            $rendered[] = $row['name'];

            return $row['name'];
        }),
    ]);

    Route::get('/customers', fn () => Inertia::render('Customers', ['customers' => $table]));

    $this->get('/customers?page=2', ['X-Inertia' => 'true'])
        ->assertOk()
        ->assertJsonCount(2, 'props.customers.rows')
        ->assertJsonPath('props.customers.rows.0.key', 'margaret')
        ->assertJsonPath('props.customers.rows.1.key', 'barbara')
        ->assertJsonPath('props.customers.pagination.currentPage', 2)
        ->assertJsonPath('props.customers.pagination.total', 5)
        ->assertJsonPath('props.customers.pagination.lastPage', 3);

    expect($rendered)->toBe(['Margaret', 'Barbara'])
        ->and($table->getSource()->get()->items->all())->toBe($items);
})->with([false, true]);

it('preserves numeric source keys between pages', function () {
    $source = new CollectionSource(['Ada', 'Grace', 'Margaret']);

    expect($source->paginate(2, 2)->items->all())->toBe([2 => 'Margaret'])
        ->and($source->get()->items->all())->toBe(['Ada', 'Grace', 'Margaret']);
});

it('supports array sources defined by a table class', function () {
    $table = new class extends Table
    {
        protected array $perPageOptions = [1, 2];

        /**
         * Define the in-memory table data.
         */
        public function source(): array
        {
            return [['name' => 'Ada'], ['name' => 'Grace']];
        }
    };

    expect($table->getResult(Request::create('/?page=2'))->items->all())->toBe([1 => ['name' => 'Grace']]);
});

it('allows disabling pagination and returning to automatic mode', function (string $source) {
    $items = $source === 'eloquent' ? Customer::query()->orderBy('id') : range(1, 60);
    $table = Table::make($items)->paginate(false);

    $result = $table->getResult(Request::create('/?page=2&limit=25'));
    expect($result->items)->toHaveCount(60)
        ->and($result->pagination)->toBeNull();

    $result = $table->paginate(null)->getResult(Request::create('/?page=2&limit=25'));
    expect($result->items)->toHaveCount(25)
        ->and($result->pagination->currentPage)->toBe(2);
})->with(['eloquent', 'array']);

it('leaves custom sources unpaginated in automatic mode', function () {
    $source = new class implements Source
    {
        /**
         * Retrieve rows from a source without pagination support.
         */
        public function get(): SourceResult
        {
            return new SourceResult(collect(range(1, 60)));
        }
    };

    $table = Table::make($source);
    $result = $table->getResult();
    expect($result->items)->toHaveCount(60)
        ->and($result->pagination)->toBeNull();

    expect(fn () => $table->paginate()->getResult())
        ->toThrow(LogicException::class, 'Paginatable');
});

it('normalizes malformed pagination parameters and rejects unlisted page sizes', function (array $query) {
    $result = Table::make(range(1, 60))->getResult(Request::create('/', 'GET', $query));

    expect($result->pagination->currentPage)->toBe(1)
        ->and($result->pagination->perPage)->toBe(25)
        ->and($result->items)->toHaveCount(25);
})->with([
    [['page' => -1, 'limit' => 1000]],
    [['page' => 0, 'limit' => 0]],
    [['page' => 'invalid', 'limit' => 'invalid']],
    [['page' => '1.5', 'limit' => '25.5']],
    [['page' => ['2'], 'limit' => ['50']]],
    [['page' => '99999999999999999999999', 'limit' => '99999999999999999999999']],
]);

it('supports allowed page sizes and a custom default', function () {
    $table = Table::make(range(1, 60))->defaultPerPage(20);
    expect($table->getResult()->items)->toHaveCount(20)
        ->and($table->getPerPageOptions())->toBe([20, 25, 50, 100]);

    $result = $table->getResult(Request::create('/?limit=50'));
    expect($result->items)->toHaveCount(50)
        ->and($result->pagination->perPage)->toBe(50);

    $table->perPageOptions([]);
    expect($table->getResult(Request::create('/?limit=50'))->items)->toHaveCount(20)
        ->and($table->getPerPageOptions())->toBeEmpty();
});

it('clamps pages to the available range for both adapters', function (string $source) {
    $adapter = $source === 'eloquent'
        ? new EloquentSource(Customer::query()->orderBy('id'))
        : new CollectionSource(range(1, 60));

    $result = $adapter->paginate(25, PHP_INT_MAX);
    expect($result->pagination->currentPage)->toBe(3)
        ->and($result->items)->toHaveCount(10);

    $empty = $source === 'eloquent'
        ? new EloquentSource(Customer::query()->where('id', '<', 0))
        : new CollectionSource([]);
    $result = $empty->paginate(25, 3);
    expect($result->pagination->currentPage)->toBe(1)
        ->and($result->pagination->lastPage)->toBe(1)
        ->and($result->pagination->total)->toBe(0)
        ->and($result->items)->toBeEmpty();
})->with(['eloquent', 'array']);

it('keeps separate query state and preserves unrelated parameters for multiple tables', function () {
    Route::get('/tables', fn () => Inertia::render('Tables', [
        'first' => Table::make(range(1, 10))->perPageOptions([2, 5])->withQueryPrefix('first_'),
        'second' => Table::make(range(1, 10))->perPageOptions([2, 5])->withQueryPrefix('second_'),
    ]));

    $response = $this->get('/tables?first_page=2&first_limit=2&second_page=3&second_limit=2&search=kept', ['X-Inertia' => 'true'])
        ->assertOk()
        ->assertJsonPath('props.first.rows.0.key', 2)
        ->assertJsonPath('props.second.rows.0.key', 4)
        ->assertJsonPath('props.first.pagination.pageName', 'first_page')
        ->assertJsonPath('props.second.pagination.perPageName', 'second_limit');

    parse_str(parse_url($response->json('props.first.pagination.nextPageUrl'), PHP_URL_QUERY), $query);
    expect($query)->toBe([
        'first_page' => '3', 'first_limit' => '2',
        'second_page' => '3', 'second_limit' => '2', 'search' => 'kept',
    ]);
});

it('does not cache rows across requests on the same table instance', function () {
    $table = Table::make(range(1, 60));
    expect($table->getResult(Request::create('/?page=1'))->items->first())->toBe(1)
        ->and($table->getResult(Request::create('/?page=2'))->items->first())->toBe(26);
});

it('rejects invalid page size configuration', function () {
    expect(fn () => Table::make([])->defaultPerPage(0))->toThrow(InvalidArgumentException::class)
        ->and(fn () => Table::make([])->perPageOptions([25, -1]))->toThrow(InvalidArgumentException::class)
        ->and(fn () => Table::make([])->perPageOptions(['25']))->toThrow(InvalidArgumentException::class);
});
