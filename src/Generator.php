<?php

namespace MohammadAlavi\LaravelOpenApi;

use Illuminate\Support\Arr;
use MohammadAlavi\LaravelOpenApi\Builders\PathsBuilder;
use MohammadAlavi\LaravelOpenApi\Factories\OpenAPIFactory;
use MohammadAlavi\LaravelOpenApi\Support\RouteCollector;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use Webmozart\Assert\Assert;

final readonly class Generator
{
    // TODO: Is this the right place for this constant?
    public const COLLECTION_DEFAULT = 'default';

    public function __construct(
        private PathsBuilder $pathsBuilder,
        private RouteCollector $routeCollector,
    ) {
    }

    public function generate(string $collection = self::COLLECTION_DEFAULT): OpenAPI
    {
        $key = 'collections.' . $collection . '.openapi';
        Assert::false(
            Arr::exists(config('openapi'), $key),
            "OpenAPI factory for collection `{$collection}` is not defined in the configuration file.",
        );

        /** @var class-string<OpenAPIFactory> $openApiFactory */
        $openApiFactory = Arr::string(config('openapi'), $key);
        Assert::isAOf($openApiFactory, OpenAPIFactory::class);

        $paths = $this->pathsBuilder->build(
            $this->routeCollector->whereInCollection($collection),
        );

        // TODO: add support for including components from the configuration file
        return $openApiFactory::create()->paths($paths);
    }
}
