<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Components;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ReusableCallbackFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ReusableParameterFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ReusableRequestBodyFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ReusableResponseFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ReusableSchemaFilter;
use MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies\ReusableSecuritySchemeFilter;
use MohammadAlavi\LaravelOpenApi\Services\ComponentCollector;
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
        $callbacks = $this->componentCollector
            ->in($this->getPathsFromConfig('callbacks'))
            ->use(new ReusableCallbackFilter())
            ->collect($collection);
        $parameters = $this->componentCollector
            ->in($this->getPathsFromConfig('parameters'))
            ->use(new ReusableParameterFilter())
            ->collect($collection);
        $requestBodies = $this->componentCollector
            ->in($this->getPathsFromConfig('request_bodies'))
            ->use(new ReusableRequestBodyFilter())
            ->collect($collection);
        $responses = $this->componentCollector
            ->in($this->getPathsFromConfig('responses'))
            ->use(new ReusableResponseFilter())
            ->collect($collection);
        $schemas = $this->componentCollector
            ->in($this->getPathsFromConfig('schemas'))
            ->use(new ReusableSchemaFilter())
            ->collect($collection);
        $securitySchemes = $this->componentCollector
            ->in($this->getPathsFromConfig('security_schemes'))
            ->use(new ReusableSecuritySchemeFilter())
            ->collect($collection);

        $components = Components::create();

        $hasAnyObjects = false;

        if (count($callbacks) > 0) {
            $hasAnyObjects = true;

            $components = $components->callbacks(...$callbacks);
        }

        if (count($parameters) > 0) {
            $hasAnyObjects = true;

            $components = $components->parameters(...$parameters);
        }

        if (count($requestBodies) > 0) {
            $hasAnyObjects = true;

            $components = $components->requestBodies(...$requestBodies);
        }

        if (count($responses) > 0) {
            $hasAnyObjects = true;
            $components = $components->responses(...$responses);
        }

        if (count($schemas) > 0) {
            $hasAnyObjects = true;
            $components = $components->schemas(...$schemas);
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
