<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    protected array $cleanupCallbacks = [];

    protected function pushCleanupCallback(callable $callback): void
    {
        $this->cleanupCallbacks[] = $callback;
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function defineEnvironment($app)
    {
        // $app['config']->set('scalar.url', '/openapi.json');
    }
}
