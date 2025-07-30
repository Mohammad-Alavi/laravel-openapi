<?php

namespace MohammadAlavi\Laragen;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use MohammadAlavi\Laragen\Support\ExampleGenerator;
use MohammadAlavi\Laragen\Support\RuleExtractor;
use MohammadAlavi\Laragen\Support\RuleToSchema;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\Operations;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

final readonly class Laragen
{
    public static function getBodyParameters(Route|string $route): ObjectRestrictor
    {
        if (is_string($route)) {
            $route = self::getRouteByUri($route);
        }
        $rules = [];
        if (!is_null($route)) {
            $rules = app(RuleExtractor::class)->extractFrom($route);
        }

        $schema = RuleToSchema::class::transform(
            $rules,
        )->compile();

        if (is_array($schema)) {
            return Schema::from($schema);
        }

        return Schema::from([]);
    }

    public static function getRouteByUri(string $uri): Route|null
    {
        $uri = ltrim($uri, '/');
        $route = collect(app(Router::class)->getRoutes())
            ->first(
                static function (Route $route) use ($uri): bool {
                    return $route->uri() === $uri;
                },
            );

        if (!is_null($route)) {
            return $route;
        }

        return null;
    }

    public static function enrichObjectWithExample(ObjectRestrictor $descriptor): ObjectRestrictor
    {
        return app(ExampleGenerator::class)->for($descriptor);
    }

    public static function generate(string $collection): OpenAPI
    {
        /** @var Generator $generator */
        $generator = app(Generator::class);
        $spec = $generator->generate($collection);

        return $spec->paths(
            Paths::create(
                ...collect($spec->getPaths()?->entries())
                ->map(
                    static function (Path $path): Path {
                        /** @var Operations $operations */
                        $operations = $path->value()->getOperations();
                        /** @var AvailableOperation[] $availableOps */
                        $availableOps = $operations->entries();
                        $ops = [];
                        foreach ($availableOps as $operation) {
                            if (!Arr::has($operation->value()->toArray(), 'requestBody')) {
                                $ops[$operation->key()] = $operation->value()->requestBody(
                                    RequestBody::create()
                                        ->content(
                                            ContentEntry::json(
                                                MediaType::create()
                                                    ->schema(
                                                        self::enrichObjectWithExample(
                                                            self::getBodyParameters(
                                                                $path->key(),
                                                            ),
                                                        ),
                                                    ),
                                            ),
                                        ),
                                );
                            } else {
                                $ops[$operation->key()] = $operation->value();
                            }
                        }

                        $processedAvailableOps = [];
                        foreach ($ops as $key => $operation) {
                            $processedAvailableOps[] = AvailableOperation::create(HttpMethod::from($key), $operation);
                        }

                        return Path::create(
                            $path->key(),
                            $path->value()->operations(...$processedAvailableOps),
                        );
                    },
                ),
            ),
        );
    }
}
