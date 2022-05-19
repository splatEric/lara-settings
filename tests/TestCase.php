<?php

namespace Camc\LaraSettings\Tests;

use Camc\LaraSettings\LaraSettingsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaraSettingsServiceProvider::class,
        ];
    }
}
