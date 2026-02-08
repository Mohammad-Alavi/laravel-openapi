<?php

namespace MohammadAlavi\Laragen\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MohammadAlavi\Laragen\Console\Generate;
use MohammadAlavi\Laragen\ExampleGenerator\Date;
use MohammadAlavi\Laragen\ExampleGenerator\Email;
use MohammadAlavi\Laragen\ExampleGenerator\ExampleProvider;
use MohammadAlavi\Laragen\ExampleGenerator\Integer;
use MohammadAlavi\Laragen\ExampleGenerator\Password;
use MohammadAlavi\Laragen\ResponseSchema\EloquentModel\EloquentModelDetector;
use MohammadAlavi\Laragen\ResponseSchema\EloquentModel\EloquentModelSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerDetector;
use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceDetector;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\ResourceCollection\ResourceCollectionDetector;
use MohammadAlavi\Laragen\ResponseSchema\ResourceCollection\ResourceCollectionSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaResolver;
use MohammadAlavi\Laragen\ResponseSchema\ResponseStrategy;
use MohammadAlavi\Laragen\ResponseSchema\SpatieData\SpatieDataDetector;
use MohammadAlavi\Laragen\ResponseSchema\SpatieData\SpatieDataSchemaBuilder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class LaragenServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laragen.php',
            'laragen',
        );

        $this->commands([
            Generate::class,
        ]);

        $this->app->singleton(ResponseSchemaResolver::class, static function (Application $app): ResponseSchemaResolver {
            $strategies = [
                new ResponseStrategy(
                    $app->make(ResourceCollectionDetector::class),
                    $app->make(ResourceCollectionSchemaBuilder::class),
                ),
                new ResponseStrategy(
                    $app->make(JsonResourceDetector::class),
                    $app->make(JsonResourceSchemaBuilder::class),
                ),
            ];

            if (class_exists('Spatie\LaravelData\Data')) {
                $strategies[] = new ResponseStrategy(
                    $app->make(SpatieDataDetector::class),
                    $app->make(SpatieDataSchemaBuilder::class),
                );
            }

            if (class_exists('League\Fractal\TransformerAbstract')) {
                $strategies[] = new ResponseStrategy(
                    $app->make(FractalTransformerDetector::class),
                    $app->make(FractalTransformerSchemaBuilder::class),
                );
            }

            $strategies[] = new ResponseStrategy(
                $app->make(EloquentModelDetector::class),
                $app->make(EloquentModelSchemaBuilder::class),
            );

            return new ResponseSchemaResolver($strategies);
        });
    }

    public function boot(): void
    {
        ExampleProvider::addExample(
            Date::class,
            Email::class,
            Password::class,
            Integer::class,
        );

        $this->publishes([
            __DIR__ . '/../../config/laragen.php' => config_path('laragen.php'),
        ], 'laragen-config');

        Route::get(
            'laragen/docs',
            static function (): BinaryFileResponse {
                return response()->file(base_path(config()->string('laragen.docs_path')));
            },
        );
    }
}
