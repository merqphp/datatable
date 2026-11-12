<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Merq\DataTable\Column;
use Merq\DataTable\ColumnCollection;
use Merq\DataTable\Columns\Text;
use Merq\DataTable\Table;
use Merq\DataTable\Tests\Fixtures\Models\Customer;
use Merq\DataTable\Tests\Fixtures\Tables\CustomerTable;

beforeEach(function () {
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->nullable();
        $table->string('internal_note')->nullable();
    });
});

it('builds text columns and rows only when Inertia resolves the table', function () {
    Customer::query()->insert([
        'id' => 42,
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'internal_note' => 'Not included in the table',
    ]);

    DB::enableQueryLog();

    $table = Table::make(Customer::query())->withColumns([
        Text::make('Name')->name('name'),
        Text::make('E-mail', 'email')->name('email'),
        Text::make('Initial', fn (Customer $customer): string => mb_substr($customer->name, 0, 1))->name('initial'),
    ]);

    $response = Inertia::render('Customers/Index', ['customers' => $table]);

    expect(DB::getQueryLog())->toBeEmpty();

    Route::get('/customers', fn () => $response);

    $this->get('/customers', ['X-Inertia' => 'true'])
        ->assertOk()
        ->assertJsonPath('props.customers', [
            'columns' => [
                ['id' => 'name', 'title' => 'Name'],
                ['id' => 'email', 'title' => 'E-mail'],
                ['id' => 'initial', 'title' => 'Initial'],
            ],
            'rows' => [
                [
                    'key' => 42,
                    'cells' => [
                        ['column' => 'name', 'component' => 'DataTable::Columns/Text', 'props' => ['value' => 'Ada Lovelace']],
                        ['column' => 'email', 'component' => 'DataTable::Columns/Text', 'props' => ['value' => 'ada@example.com']],
                        ['column' => 'initial', 'component' => 'DataTable::Columns/Text', 'props' => ['value' => 'A']],
                    ],
                ],
            ],
        ]);

    expect(DB::getQueryLog())->toHaveCount(1);
});

it('builds a class-defined table with additional and modified columns', function () {
    Customer::query()->insert(['id' => 7, 'name' => 'Grace Hopper']);

    $table = CustomerTable::make()
        ->withColumns(collect([Text::make('E-mail', 'email')->name('email')]))
        ->withColumns(fn (ColumnCollection $columns) => $columns
            ->prepend(Text::make('ID', 'id')->name('id'))
        );

    Route::get('/customers', fn () => Inertia::render('Customers/Index', ['customers' => $table]));

    $this->get('/customers', ['X-Inertia' => 'true'])
        ->assertOk()
        ->assertJsonPath('props.customers', [
            'columns' => [
                ['id' => 'id', 'title' => 'ID'],
                ['id' => 'name', 'title' => 'Name'],
                ['id' => 'email', 'title' => 'E-mail'],
            ],
            'rows' => [
                [
                    'key' => 7,
                    'cells' => [
                        ['column' => 'id', 'component' => 'DataTable::Columns/Text', 'props' => ['value' => 7]],
                        ['column' => 'name', 'component' => 'DataTable::Columns/Text', 'props' => ['value' => 'Grace Hopper']],
                        ['column' => 'email', 'component' => 'DataTable::Columns/Text', 'props' => ['value' => null]],
                    ],
                ],
            ],
        ]);
});

it('keeps the column definitions when the source is empty', function () {
    Route::get('/customers', fn () => Inertia::render('Customers/Index', [
        'customers' => Table::make(Customer::query())->withColumns([Text::make('Name')]),
    ]));

    $this->get('/customers', ['X-Inertia' => 'true'])
        ->assertOk()
        ->assertJsonPath('props.customers', [
            'columns' => [
                ['id' => 'col-01-name', 'title' => 'Name'],
            ],
            'rows' => [],
        ]);
});

it('does not query the source when a partial reload excludes the table', function () {
    Route::get('/customers', fn () => Inertia::render('Customers/Index', [
        'customers' => CustomerTable::make(),
        'status' => 'ready',
    ]));

    DB::enableQueryLog();

    $this->get('/customers', [
        'X-Inertia' => 'true',
        'X-Inertia-Partial-Component' => 'Customers/Index',
        'X-Inertia-Partial-Data' => 'status',
    ])
        ->assertOk()
        ->assertJsonPath('props.status', 'ready')
        ->assertJsonMissingPath('props.customers');

    expect(DB::getQueryLog())->toBeEmpty();
});

it('builds custom component props from the resolved value and resource', function () {
    Customer::query()->insert(['id' => 42, 'name' => 'Ada Lovelace']);

    $column = new class('Profile', 'name') extends Column
    {
        /**
         * Retrieve the consumer component name.
         */
        public function component(): string
        {
            return 'App::Columns/Profile';
        }

        /**
         * Build the profile link from the value and resource.
         */
        public function toView(mixed $value, mixed $resource): array
        {
            return ['label' => $value, 'href' => '/customers/'.$resource->getKey()];
        }
    };

    Route::get('/customers', fn () => Inertia::render('Customers/Index', [
        'customers' => Table::make(Customer::query())->withColumns([$column->name('profile')]),
    ]));

    $this->get('/customers', ['X-Inertia' => 'true'])
        ->assertOk()
        ->assertJsonPath('props.customers.rows.0.cells.0', [
            'column' => 'profile',
            'component' => 'App::Columns/Profile',
            'props' => ['label' => 'Ada Lovelace', 'href' => '/customers/42'],
        ]);
});

it('serializes empty component props as an object', function () {
    Customer::query()->insert(['id' => 42, 'name' => 'Ada Lovelace']);

    $column = new class('Actions') extends Column
    {
        /**
         * Retrieve the consumer component name.
         */
        public function component(): string
        {
            return 'App::Columns/Actions';
        }

        /**
         * Define a component without props.
         */
        public function toView(mixed $value, mixed $resource): array
        {
            return [];
        }
    };

    Route::get('/customers', fn () => Inertia::render('Customers/Index', [
        'customers' => Table::make(Customer::query())->withColumns([$column]),
    ]));

    $response = $this->get('/customers', ['X-Inertia' => 'true'])->assertOk();
    $cell = json_decode($response->getContent())->props->customers->rows[0]->cells[0];

    expect($cell->props)->toBeInstanceOf(stdClass::class)
        ->and((array) $cell->props)->toBeEmpty();
});
