<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Workbench\App\Documentation\Workbench;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->app['config']->set('openapi.collections.Workbench', [
            'openapi' => Workbench::class,
        ]);
        $this->app['config']->set('scalar', require __DIR__ . '/../../../config/scalar.php');
        $this->app['config']->set('scribe', require __DIR__ . '/../../../config/scribe.php');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/console.php');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'workbench');
    }
}
