<?php

namespace MohammadAlavi\LaravelOpenApi;

use Illuminate\Support\Arr;
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
        private PathsBuilder $pathsBuilder,
        private ComponentsBuilder $componentsBuilder,
        private RouteCollector $routeCollector,
    ) {
    }

    public function generate(string $collection = self::COLLECTION_DEFAULT): OpenAPI
    {
        $paths = $this->pathsBuilder->build(
            $this->routeCollector->whereInCollection($collection),
        );
        $components = $this->componentsBuilder->build($collection);

        /** @var class-string<OpenAPIFactory> $openApiFactory */
        $openApiFactory = Arr::string(config('openapi'), 'collections.' . $collection . '.openapi');
        Assert::isAOf($openApiFactory, OpenAPIFactory::class);

        $openApi = $openApiFactory::create()->paths($paths);

        if ($components instanceof Components) {
            return $openApi->components($components);
        }

        return $openApi;
    }
}
