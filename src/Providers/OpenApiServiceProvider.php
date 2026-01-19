<?php

namespace MohammadAlavi\LaravelOpenApi\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MohammadAlavi\LaravelOpenApi\Console\CallbackFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\ExtensionFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\GenerateCommand;
use MohammadAlavi\LaravelOpenApi\Console\ParametersFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\RequestBodyFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\ResponseFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\SchemaFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\SecuritySchemeFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Http\OpenApiController;

class OpenApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/openapi.php',
            'openapi',
        );

        $this->commands([
            GenerateCommand::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CallbackFactoryMakeCommand::class,
                ExtensionFactoryMakeCommand::class,
                ParametersFactoryMakeCommand::class,
                RequestBodyFactoryMakeCommand::class,
                ResponseFactoryMakeCommand::class,
                SchemaFactoryMakeCommand::class,
                SecuritySchemeFactoryMakeCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/openapi.php' => config_path('openapi.php'),
        ], 'openapi-config');

        // Routes can be disabled per-collection by omitting 'route.uri' in config.
        Route::group(['as' => 'openapi.'], static function (): void {
            foreach (config('openapi.collections', []) as $name => $config) {
                $uri = Arr::get($config, 'route.uri');

                if (!$uri) {
                    continue;
                }

                Route::get($uri, [OpenApiController::class, 'show'])
                    ->name($name . '.specification')
                    ->prefix('/api')
                    ->middleware(['api', ...Arr::get($config, 'route.middleware')]);
            }
        });
    }
}
