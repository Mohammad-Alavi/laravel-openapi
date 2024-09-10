<?php

namespace MohammadAlavi\LaravelOpenApi;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use MohammadAlavi\LaravelOpenApi\Collectors\Component\CallbackCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Component\ClassCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Component\RequestBodyCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Component\ResponseCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Component\SchemaCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Component\SecuritySchemeCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\ComponentCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\InfoBuilder;
use MohammadAlavi\LaravelOpenApi\Collectors\PathBuilder;
use MohammadAlavi\LaravelOpenApi\Collectors\ServerBuilder;
use MohammadAlavi\LaravelOpenApi\Collectors\TagBuilder;

class OpenApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/openapi.php',
            'openapi',
        );

        $this->app->singleton(CallbackCollector::class, function (Application $app) {
            return new CallbackCollector(new ClassCollector($this->getPathsFromConfig('callbacks')));
        });

        $this->app->singleton(RequestBodyCollector::class, function (Application $app) {
            return new RequestBodyCollector(new ClassCollector($this->getPathsFromConfig('request_bodies')));
        });

        $this->app->singleton(ResponseCollector::class, function (Application $app) {
            return new ResponseCollector(new ClassCollector($this->getPathsFromConfig('responses')));
        });

        $this->app->singleton(SchemaCollector::class, function (Application $app) {
            return new SchemaCollector(new ClassCollector($this->getPathsFromConfig('schemas')));
        });

        $this->app->singleton(SecuritySchemeCollector::class, function (Application $app) {
            return new SecuritySchemeCollector(new ClassCollector($this->getPathsFromConfig('security_schemes')));
        });

        $this->app->singleton(Generator::class, static function (Application $app) {
            $config = config('openapi');

            return new Generator(
                $config,
                $app->make(InfoBuilder::class),
                $app->make(ServerBuilder::class),
                $app->make(TagBuilder::class),
                $app->make(PathBuilder::class),
                $app->make(ComponentCollector::class),
            );
        });

        $this->commands([
            Console\GenerateCommand::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\CallbackFactoryMakeCommand::class,
                Console\ExtensionFactoryMakeCommand::class,
                Console\ParametersFactoryMakeCommand::class,
                Console\RequestBodyFactoryMakeCommand::class,
                Console\ResponseFactoryMakeCommand::class,
                Console\SchemaFactoryMakeCommand::class,
                Console\SecuritySchemeFactoryMakeCommand::class,
            ]);
        }
    }

    private function getPathsFromConfig(string $type): array
    {
        $directories = config('openapi.locations.' . $type, []);

        foreach ($directories as &$directory) {
            $directory = glob($directory, GLOB_ONLYDIR);
        }

        return Collection::make($directories)
            ->flatten()
            ->unique()
            ->toArray();
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/openapi.php' => config_path('openapi.php'),
            ], 'openapi-config');
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
