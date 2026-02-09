<?php

namespace MohammadAlavi\Laragen\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MohammadAlavi\Laragen\Console\Generate;
use MohammadAlavi\Laragen\ExampleGenerator\Date;
use MohammadAlavi\Laragen\ExampleGenerator\Email;
use MohammadAlavi\Laragen\ExampleGenerator\ExampleRegistry;
use MohammadAlavi\Laragen\ExampleGenerator\Integer;
use MohammadAlavi\Laragen\ExampleGenerator\Password;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResolver;
use MohammadAlavi\Laragen\RequestSchema\RequestStrategy;
use MohammadAlavi\Laragen\RequestSchema\SpatieData\SpatieDataRequestDetector;
use MohammadAlavi\Laragen\RequestSchema\SpatieData\SpatieDataRequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\ValidationRules\ValidationRulesDetector;
use MohammadAlavi\Laragen\RequestSchema\ValidationRules\ValidationRulesSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\EloquentModel\EloquentModelDetector;
use MohammadAlavi\Laragen\ResponseSchema\EloquentModel\EloquentModelSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerDetector;
use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceDetector;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\ResourceCollection\ResourceCollectionDetector;
use MohammadAlavi\Laragen\ResponseSchema\ResourceCollection\ResourceCollectionSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
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

        $this->registerExampleRegistry();
        $this->registerRequestSchemaResolver();
        $this->registerResponseSchemaResolver();
    }

    public function boot(): void
    {
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

    private function registerExampleRegistry(): void
    {
        $this->app->singleton(ExampleRegistry::class, static function (): ExampleRegistry {
            $builtIn = [
                Date::rule() => Date::class,
                Email::rule() => Email::class,
                Password::rule() => Password::class,
                Integer::rule() => Integer::class,
            ];

            return new ExampleRegistry($builtIn);
        });
    }

    private function registerRequestSchemaResolver(): void
    {
        $this->app->singleton(RequestSchemaResolver::class, static function (Application $app): RequestSchemaResolver {
            /** @var array<int, array{0: class-string, 1: class-string}> $prepend */
            $prepend = config('laragen.strategies.request.prepend', []);
            /** @var array<int, array{0: class-string, 1: class-string}> $append */
            $append = config('laragen.strategies.request.append', []);

            $prependStrategies = self::buildRequestStrategiesFromConfig($app, $prepend);

            $builtInStrategies = [];

            if (class_exists('Spatie\LaravelData\Data')) {
                $builtInStrategies[] = new RequestStrategy(
                    $app->make(SpatieDataRequestDetector::class),
                    $app->make(SpatieDataRequestSchemaBuilder::class),
                );
            }

            $builtInStrategies[] = new RequestStrategy(
                $app->make(ValidationRulesDetector::class),
                $app->make(ValidationRulesSchemaBuilder::class),
            );

            $appendStrategies = self::buildRequestStrategiesFromConfig($app, $append);

            return new RequestSchemaResolver([
                ...$prependStrategies,
                ...$builtInStrategies,
                ...$appendStrategies,
            ]);
        });
    }

    private function registerResponseSchemaResolver(): void
    {
        $this->app->singleton(ResponseSchemaResolver::class, static function (Application $app): ResponseSchemaResolver {
            /** @var array<int, array{0: class-string, 1: class-string}> $prepend */
            $prepend = config('laragen.strategies.response.prepend', []);
            /** @var array<int, array{0: class-string, 1: class-string}> $append */
            $append = config('laragen.strategies.response.append', []);

            $prependStrategies = self::buildResponseStrategiesFromConfig($app, $prepend);

            $builtInStrategies = [
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
                $builtInStrategies[] = new ResponseStrategy(
                    $app->make(SpatieDataDetector::class),
                    $app->make(SpatieDataSchemaBuilder::class),
                );
            }

            if (class_exists('League\Fractal\TransformerAbstract')) {
                $builtInStrategies[] = new ResponseStrategy(
                    $app->make(FractalTransformerDetector::class),
                    $app->make(FractalTransformerSchemaBuilder::class),
                );
            }

            $builtInStrategies[] = new ResponseStrategy(
                $app->make(EloquentModelDetector::class),
                $app->make(EloquentModelSchemaBuilder::class),
            );

            $appendStrategies = self::buildResponseStrategiesFromConfig($app, $append);

            return new ResponseSchemaResolver([
                ...$prependStrategies,
                ...$builtInStrategies,
                ...$appendStrategies,
            ]);
        });
    }

    /**
     * @param array<int, array{0: class-string, 1: class-string}> $config
     *
     * @return RequestStrategy[]
     */
    private static function buildRequestStrategiesFromConfig(Application $app, array $config): array
    {
        return array_map(
            static function (array $pair) use ($app): RequestStrategy {
                /** @var RequestDetector $detector */
                $detector = $app->make($pair[0]);
                /** @var RequestSchemaBuilder $builder */
                $builder = $app->make($pair[1]);

                return new RequestStrategy($detector, $builder);
            },
            $config,
        );
    }

    /**
     * @param array<int, array{0: class-string, 1: class-string}> $config
     *
     * @return ResponseStrategy[]
     */
    private static function buildResponseStrategiesFromConfig(Application $app, array $config): array
    {
        return array_map(
            static function (array $pair) use ($app): ResponseStrategy {
                /** @var ResponseDetector $detector */
                $detector = $app->make($pair[0]);
                /** @var ResponseSchemaBuilder $builder */
                $builder = $app->make($pair[1]);

                return new ResponseStrategy($detector, $builder);
            },
            $config,
        );
    }
}
