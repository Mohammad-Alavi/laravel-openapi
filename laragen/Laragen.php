<?php

namespace MohammadAlavi\Laragen;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use MohammadAlavi\Laragen\Auth\AuthDetector;
use MohammadAlavi\Laragen\Auth\SecuritySchemeRegistry;
use MohammadAlavi\Laragen\ExampleGenerator\ExampleGenerator;
use MohammadAlavi\Laragen\PathParameters\PathParameterAnalyzer;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceDetector;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceSchemaBuilder;
use MohammadAlavi\Laragen\RouteDiscovery\AutoRouteCollector;
use MohammadAlavi\Laragen\RouteDiscovery\PatternMatcher;
use MohammadAlavi\Laragen\Support\Config\Config;
use MohammadAlavi\Laragen\Support\RuleToSchema;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\ComponentsBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\PathsBuilder;
use MohammadAlavi\LaravelOpenApi\Factories\OpenAPIFactory;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\LaravelOpenApi\Support\RouteCollector;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\Operations;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use Webmozart\Assert\Assert;

final readonly class Laragen
{
    /**
     * @param non-empty-string $collection
     */
    public static function generate(string $collection): OpenAPI
    {
        $mode = config()->string('laragen.route_discovery.mode', 'attribute');
        $spec = self::buildBaseSpec($collection, $mode);

        return self::enrichSpec($spec);
    }

    /**
     * @param non-empty-string $collection
     */
    private static function buildBaseSpec(string $collection, string $mode): OpenAPI
    {
        if ('attribute' === $mode) {
            return app(Generator::class)->generate($collection);
        }

        $autoRoutes = self::collectAutoRoutes();

        if ('auto' === $mode) {
            return self::buildSpecFromRoutes($autoRoutes, $collection);
        }

        // combined: merge attribute-discovered and auto-discovered routes
        $attributeRoutes = app(RouteCollector::class)->whereShouldBeCollectedFor($collection);
        $merged = self::mergeRoutes($attributeRoutes, $autoRoutes);

        return self::buildSpecFromRoutes($merged, $collection);
    }

    /**
     * @return Collection<int, RouteInfo>
     */
    private static function collectAutoRoutes(): Collection
    {
        /** @var string[] $include */
        $include = config('laragen.route_discovery.include', ['api/*']);
        /** @var string[] $exclude */
        $exclude = config('laragen.route_discovery.exclude', []);

        $matcher = new PatternMatcher($include, $exclude);

        return app(AutoRouteCollector::class)->collect($matcher);
    }

    /**
     * @param Collection<int, RouteInfo> $routes
     * @param non-empty-string $collection
     */
    private static function buildSpecFromRoutes(Collection $routes, string $collection): OpenAPI
    {
        /** @var class-string<OpenAPIFactory> $openApiFactory */
        $openApiFactory = config()->string('openapi.collections.' . $collection . '.openapi');
        Assert::isAOf($openApiFactory, OpenAPIFactory::class);

        $paths = app(PathsBuilder::class)->build($routes);
        $openApi = $openApiFactory::create()->paths($paths);

        $components = app(ComponentsBuilder::class)->build($collection);
        if ($components instanceof Components) {
            return $openApi->components($components);
        }

        return $openApi;
    }

    /**
     * Merge two route collections, deduplicating by URI+method.
     *
     * @param Collection<int, RouteInfo> $primary
     * @param Collection<int, RouteInfo> $secondary
     *
     * @return Collection<int, RouteInfo>
     */
    private static function mergeRoutes(Collection $primary, Collection $secondary): Collection
    {
        $seen = $primary->mapWithKeys(
            static fn (RouteInfo $r): array => [$r->method() . ':' . $r->uri() => true],
        );

        $unique = $secondary->filter(
            static fn (RouteInfo $r): bool => !$seen->has($r->method() . ':' . $r->uri()),
        );

        return $primary->merge($unique)->values();
    }

    private static function enrichSpec(OpenAPI $spec): OpenAPI
    {
        $authDetector = app(AuthDetector::class);
        $securityRegistry = app(SecuritySchemeRegistry::class);
        $pathParameterAnalyzer = app(PathParameterAnalyzer::class);
        $responseDetector = app(JsonResourceDetector::class);
        $responseSchemaBuilder = app(JsonResourceSchemaBuilder::class);
        $securityEnabled = config()->boolean('laragen.autogen.security');
        $pathParamsEnabled = config()->boolean('laragen.autogen.path_parameters');
        $responseEnabled = config()->boolean('laragen.autogen.response');

        /** @var Path[] $pathEntries */
        $pathEntries = $spec->getPaths()?->entries() ?? [];

        return $spec->paths(
            Paths::create(
                ...collect($pathEntries)
                ->map(
                    static function (Path $path) use ($authDetector, $securityRegistry, $pathParameterAnalyzer, $responseDetector, $responseSchemaBuilder, $securityEnabled, $pathParamsEnabled, $responseEnabled): Path {
                        /** @var Operations $operations */
                        $operations = $path->value()->getOperations();
                        /** @var AvailableOperation[] $availableOperations */
                        $availableOperations = $operations->entries();
                        $processedAvailableOps = [];
                        $pathRoute = null;

                        foreach ($availableOperations as $availableOperation) {
                            $route = self::getRouteByUri($availableOperation->key(), $path->key());
                            $operation = $availableOperation->value();

                            if (null === $pathRoute && null !== $route) {
                                $pathRoute = $route;
                            }

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

                            if ($responseEnabled && !is_null($route) && !self::hasResponses($operation)) {
                                $operation = self::enrichWithResponse(
                                    $operation,
                                    $route,
                                    $responseDetector,
                                    $responseSchemaBuilder,
                                );
                            }

                            $processedAvailableOps[] = AvailableOperation::create(
                                HttpMethod::from($availableOperation->key()),
                                $operation,
                            );
                        }

                        $pathItem = $path->value()->operations(...$processedAvailableOps);

                        if ($pathParamsEnabled && null !== $pathRoute) {
                            $parameters = $pathParameterAnalyzer->analyze($pathRoute);

                            if ([] !== $parameters) {
                                $pathItem = $pathItem->parameters(
                                    Parameters::create(...$parameters),
                                );
                            }
                        }

                        return Path::create($path->key(), $pathItem);
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
            return app(ExampleGenerator::class)->for($descriptor); // @phpstan-ignore argument.type, return.type (runtime type is StrictFluentDescriptor which satisfies both)
        }

        return $descriptor;
    }

    private static function hasResponses(Operation $operation): bool
    {
        return Arr::has($operation->compile(), 'responses');
    }

    private static function enrichWithResponse(
        Operation $operation,
        Route $route,
        JsonResourceDetector $responseDetector,
        JsonResourceSchemaBuilder $responseSchemaBuilder,
    ): Operation {
        $actionName = $route->getActionName();

        if (!str_contains($actionName, '@')) {
            return $operation;
        }

        [$controllerClass, $method] = explode('@', $actionName, 2);

        /** @var class-string $controllerClass */
        $resourceClass = $responseDetector->detect($controllerClass, $method);

        if (null === $resourceClass) {
            return $operation;
        }

        $schema = $responseSchemaBuilder->build($resourceClass);

        return $operation->responses(
            Responses::create(
                ResponseEntry::create(
                    HTTPStatusCode::ok(),
                    Response::create()
                        ->description('Successful response')
                        ->content(
                            ContentEntry::json(
                                MediaType::create()->schema($schema),
                            ),
                        ),
                ),
            ),
        );
    }

    public static function configs(): Config
    {
        return new Config();
    }
}
