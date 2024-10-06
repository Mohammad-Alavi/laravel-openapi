<?php

namespace MohammadAlavi\LaravelOpenApi;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MohammadAlavi\LaravelOpenApi\Builders\InfoBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\PathsBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\ServerBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\TagBuilder;
use MohammadAlavi\LaravelOpenApi\Console\CallbackFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\ExtensionFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\GenerateCommand;
use MohammadAlavi\LaravelOpenApi\Console\ParametersFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\RequestBodyFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\ResponseFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\SchemaFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Console\SecuritySchemeFactoryMakeCommand;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\RouteCollector;
use MohammadAlavi\LaravelOpenApi\Http\OpenApiController;
use MohammadAlavi\LaravelOpenApi\Reusable\ComponentsBuilder;

class OpenApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/openapi.php',
            'openapi',
        );

        $this->app->singleton(
            Generator::class,
            static function (Application $application): Generator {
                $config = config(
                    'openapi',
                );

                return new Generator(
                    $config,
                    $application->make(InfoBuilder::class),
                    $application->make(ServerBuilder::class),
                    $application->make(TagBuilder::class),
                    $application->make(PathsBuilder::class),
                    $application->make(ComponentsBuilder::class),
                );
            },
        );

        $this->app->bind(RouteCollector::class, Services\RouteCollector::class);

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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/openapi.php' => config_path('openapi.php'),
            ], 'openapi-config');
        }

        // TODO: allow to disable this, so user can register their own routes.
        //  Like how Laravel Passport does it.
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
