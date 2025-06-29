<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Components;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\CallbackFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ExampleFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ParameterFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\RequestBodyFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ResponseFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\SchemaFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\SecuritySchemeFilter;
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
            ->in($this->getPathsFromConfig('schemas'))
            ->use(new SchemaFilter())
            ->collect($collection);
        $responses = $this->componentCollector
            ->in($this->getPathsFromConfig('responses'))
            ->use(new ResponseFilter())
            ->collect($collection);
        $parameters = $this->componentCollector
            ->in($this->getPathsFromConfig('parameters'))
            ->use(new ParameterFilter())
            ->collect($collection);
        $examples = $this->componentCollector
            ->in($this->getPathsFromConfig('examples'))
            ->use(new ExampleFilter())
            ->collect($collection);
        $callbacks = $this->componentCollector
            ->in($this->getPathsFromConfig('callbacks'))
            ->use(new CallbackFilter())
            ->collect($collection);
        $requestBodies = $this->componentCollector
            ->in($this->getPathsFromConfig('request_bodies'))
            ->use(new RequestBodyFilter())
            ->collect($collection);
        $securitySchemes = $this->componentCollector
            ->in($this->getPathsFromConfig('security_schemes'))
            ->use(new SecuritySchemeFilter())
            ->collect($collection);

        $components = Components::create();

        $hasAnyObjects = false;

        if (count($schemas) > 0) {
            $hasAnyObjects = true;
            $components = $components->schemas(...$schemas);
        }

        if (count($responses) > 0) {
            $hasAnyObjects = true;
            $components = $components->responses(...$responses);
        }

        if (count($parameters) > 0) {
            $hasAnyObjects = true;

            $components = $components->parameters(...$parameters);
        }

        if (count($examples) > 0) {
            $hasAnyObjects = true;
            $components = $components->examples(...$examples);
        }

        if (count($callbacks) > 0) {
            $hasAnyObjects = true;

            $components = $components->callbacks(...$callbacks);
        }

        if (count($requestBodies) > 0) {
            $hasAnyObjects = true;

            $components = $components->requestBodies(...$requestBodies);
        }

        if (count($securitySchemes) > 0) {
            $hasAnyObjects = true;
            $components = $components->securitySchemes(...$securitySchemes);
        }

        if (!$hasAnyObjects) {
            return null;
        }

        return $components;
    }

    private function getPathsFromConfig(string $type): array
    {
        $directories = config('openapi.locations.' . $type, []);

        foreach ($directories as &$directory) {
            $directory = glob($directory, GLOB_ONLYDIR);
        }

        return Collection::make($directories)
            ->flatten()
            ->unique()
            ->toArray();
    }
}
