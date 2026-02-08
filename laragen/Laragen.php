<?php

namespace MohammadAlavi\Laragen;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use MohammadAlavi\Laragen\Auth\AuthDetector;
use MohammadAlavi\Laragen\Auth\SecuritySchemeRegistry;
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

        $authDetector = app(AuthDetector::class);
        $securityRegistry = app(SecuritySchemeRegistry::class);
        $securityEnabled = config()->boolean('laragen.autogen.security');

        return $spec->paths(
            Paths::create(
                ...collect($spec->getPaths()?->entries())
                ->map(
                    static function (Path $path) use ($authDetector, $securityRegistry, $securityEnabled): Path {
                        /** @var Operations $operations */
                        $operations = $path->value()->getOperations();
                        /** @var AvailableOperation[] $availableOperations */
                        $availableOperations = $operations->entries();
                        $processedAvailableOps = [];

                        foreach ($availableOperations as $availableOperation) {
                            $route = self::getRouteByUri($availableOperation->key(), $path->key());
                            $operation = $availableOperation->value();

                            if (!is_null($route) && !self::hasRequestBody($availableOperation)) {
                                $schema = self::extractRequestBodySchema($route);

                                if (self::hasAtLeastOneProperty($schema)) {
                                    $operation = $operation->requestBody(
                                        RequestBody::create(
                                            ContentEntry::json(
                                                MediaType::create()
                                                    ->schema(self::enrichObjectWithExample($schema)),
                                            ),
                                        ),
                                    );
                                }
                            }

                            if ($securityEnabled && !is_null($route) && !self::hasSecurity($operation)) {
                                $authScheme = $authDetector->detect($route);

                                if (null !== $authScheme) {
                                    $operation = $operation->security(
                                        $securityRegistry->securityFor($authScheme),
                                    );
                                }
                            }

                            $processedAvailableOps[] = AvailableOperation::create(
                                HttpMethod::from($availableOperation->key()),
                                $operation,
                            );
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

    private static function hasSecurity(Operation $operation): bool
    {
        return Arr::has($operation->compile(), 'security');
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
