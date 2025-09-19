<?php

namespace MohammadAlavi\Laragen;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use MohammadAlavi\Laragen\ExampleGenerator\ExampleGenerator;
use MohammadAlavi\Laragen\Support\Config\Config;
use MohammadAlavi\Laragen\Support\RuleToSchema;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
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
                        /** @var AvailableOperation[] $availableOperations */
                        $availableOperations = $operations->entries();
                        $operationMap = [];
                        foreach ($availableOperations as $availableOperation) {
                            $route = self::getRouteByUri($availableOperation->key(), $path->key());

                            if (is_null($route) || self::hasRequestBody($availableOperation)) {
                                $operationMap[$availableOperation->key()] = $availableOperation->value();
                            } else {
                                $schema = self::extractRequestBodySchema($route);

                                $operationMap[$availableOperation->key()] = self::hasAtLeastOneProperty($schema)
                                    ? self::setRequestBody($availableOperation, self::enrichObjectWithExample($schema))
                                    : $availableOperation->value();
                            }
                        }

                        $processedAvailableOps = [];
                        foreach ($operationMap as $key => $operation) {
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

    public static function getRouteByUri(string $method, string $uri): Route|null
    {
        $uri = ltrim($uri, '/');

        return collect(app(Router::class)->getRoutes()->get(strtoupper($method)))
            ->first(
                static function (Route $route) use ($uri): bool {
                    return $route->uri() === $uri;
                },
            );
    }

    private static function hasRequestBody(AvailableOperation $operation): bool
    {
        return Arr::has($operation->value()->compile(), 'requestBody');
    }

    public static function extractRequestBodySchema(Route $route): ObjectRestrictor
    {
        $schema = RuleToSchema::transform(
            $route,
        )->compile();

        if (is_array($schema)) {
            return Schema::from($schema);
        }

        return Schema::from([]);
    }

    private static function hasAtLeastOneProperty(ObjectRestrictor $schema): bool
    {
        $requestBody = $schema->compile();

        return Arr::has($requestBody, 'properties') && filled($requestBody['properties']);
    }

    private static function setRequestBody(
        AvailableOperation $availableOperation,
        ObjectRestrictor $requestBodySchema,
    ): Operation {
        return $availableOperation
            ->value()
            ->requestBody(
                RequestBody::create()
                    ->content(
                        ContentEntry::json(
                            MediaType::create()
                                ->schema($requestBodySchema),
                        ),
                    ),
            );
    }

    public static function enrichObjectWithExample(ObjectRestrictor $descriptor): ObjectRestrictor
    {
        if (config()->boolean('laragen.autogen.example')) {
            return app(ExampleGenerator::class)->for($descriptor);
        }

        return $descriptor;
    }

    public static function configs(): Config
    {
        return new Config();
    }
}
