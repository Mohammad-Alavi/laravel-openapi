<?php

namespace Tests;

use MohammadAlavi\LaravelOpenApi\Providers\OpenApiServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected array $cleanupCallbacks = [];

    protected function getPackageProviders($app): array
    {
        return [
            OpenApiServiceProvider::class,
        ];
    }

    protected function pushCleanupCallback(callable $callback): void
    {
        $this->cleanupCallbacks[] = $callback;
    }
}
