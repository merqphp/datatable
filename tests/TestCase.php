<?php

namespace Merq\DataTable\Tests;

use Inertia\ServiceProvider as InertiaServiceProvider;
use Merq\DataTable\DataTableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Register the package and Inertia service providers.
     */
    protected function getPackageProviders($app): array
    {
        return [DataTableServiceProvider::class, InertiaServiceProvider::class];
    }

    /**
     * Configure an in-memory database for package tests.
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }
}
