<?php

namespace MohammadAlavi\LaravelOpenApi;

use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\ComponentsBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\PathsBuilder;
use MohammadAlavi\LaravelOpenApi\Factories\OpenAPIFactory;
use MohammadAlavi\LaravelOpenApi\Support\RouteCollector;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use Webmozart\Assert\Assert;

final readonly class Generator
{
    // TODO: Is this the right place for this constant?
    public const COLLECTION_DEFAULT = 'default';

    public function __construct(
        private ComponentsBuilder $componentsBuilder,
        private PathsBuilder $pathsBuilder,
        private RouteCollector $routeCollector,
    ) {
    }

    public function generate(string|null $collection = self::COLLECTION_DEFAULT): OpenAPI
    {
        /** @var class-string<OpenAPIFactory> $openApiFactory */
        $openApiFactory = config()->string('openapi.collections.' . $collection . '.openapi');
        Assert::isAOf($openApiFactory, OpenAPIFactory::class);

        if (is_null($collection)) {
            $routes = $this->routeCollector->all();
        } else {
            $routes = $this->routeCollector->whereShouldBeCollectedFor($collection);
        }

        $paths = $this->pathsBuilder->build($routes);

        $openApi = $openApiFactory::create()->paths($paths);

        $components = $this->componentsBuilder->build($collection);
        if ($components instanceof Components) {
            return $openApi->components($components);
        }

        return $openApi;
    }
}
