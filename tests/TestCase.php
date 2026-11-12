<?php

namespace Merq\DataTable\Tests;

use Merq\DataTable\DataTableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [DataTableServiceProvider::class];
    }
}
