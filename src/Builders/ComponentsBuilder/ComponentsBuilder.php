<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\CallbackFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\ExampleFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\HeaderFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\LinkFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\ParameterFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\PathItemFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\RequestBodyFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\ResponseFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\SchemaFilter;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies\SecuritySchemeFilter;
use MohammadAlavi\LaravelOpenApi\Support\ComponentCollector;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;

// TODO: add protection against invalid or duplicate component names
// For now they are overwritten silently
final readonly class ComponentsBuilder
{
    public function __construct(
        private ComponentCollector $componentCollector,
    ) {
    }

    public function build(string $collection): Components|null
    {
        // TODO: Separate the collecting logic into a separate class
        $schemas = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'schemas'))
            ->use(new SchemaFilter())
            ->collect($collection);
        $responses = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'responses'))
            ->use(new ResponseFilter())
            ->collect($collection);
        $parameters = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'parameters'))
            ->use(new ParameterFilter())
            ->collect($collection);
        $examples = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'examples'))
            ->use(new ExampleFilter())
            ->collect($collection);
        $requestBodies = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'request_bodies'))
            ->use(new RequestBodyFilter())
            ->collect($collection);
        $headers = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'headers'))
            ->use(new HeaderFilter())
            ->collect($collection);
        $securitySchemes = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'security_schemes'))
            ->use(new SecuritySchemeFilter())
            ->collect($collection);
        $links = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'links'))
            ->use(new LinkFilter())
            ->collect($collection);
        $callbacks = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'callbacks'))
            ->use(new CallbackFilter())
            ->collect($collection);
        $pathItems = $this->componentCollector
            ->in($this->getPathsFromConfig($collection, 'path_items'))
            ->use(new PathItemFilter())
            ->collect($collection);

        $components = Components::create();

        $hasAnyObjects = false;

        if ($schemas->isNotEmpty()) {
            $hasAnyObjects = true;
            $components = $components->schemas(...$schemas);
        }

        if ($responses->isNotEmpty()) {
            $hasAnyObjects = true;
            $components = $components->responses(...$responses);
        }

        if ($parameters->isNotEmpty()) {
            $hasAnyObjects = true;

            $components = $components->parameters(...$parameters);
        }

        if ($examples->isNotEmpty()) {
            $hasAnyObjects = true;
            $components = $components->examples(...$examples);
        }

        if ($requestBodies->isNotEmpty()) {
            $hasAnyObjects = true;

            $components = $components->requestBodies(...$requestBodies);
        }

        if ($headers->isNotEmpty()) {
            $hasAnyObjects = true;

            $components = $components->headers(...$headers);
        }

        if ($securitySchemes->isNotEmpty()) {
            $hasAnyObjects = true;
            $components = $components->securitySchemes(...$securitySchemes);
        }

        if ($links->isNotEmpty()) {
            $hasAnyObjects = true;

            $components = $components->links(...$links);
        }

        if ($callbacks->isNotEmpty()) {
            $hasAnyObjects = true;

            $components = $components->callbacks(...$callbacks);
        }

        if ($pathItems->isNotEmpty()) {
            $hasAnyObjects = true;

            $components = $components->pathItems(...$pathItems);
        }

        if (!$hasAnyObjects) {
            return null;
        }

        return $components;
    }

    private function getPathsFromConfig(string $collection, string $type): array
    {
        $paths = config("openapi.collections.{$collection}.components.{$type}", []);

        foreach ($paths as &$path) {
            $path = \Safe\glob($path, GLOB_ONLYDIR);
        }

        return Collection::make($paths)
            ->flatten()
            ->unique()
            ->toArray();
    }
}
