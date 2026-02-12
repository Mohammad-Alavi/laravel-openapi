<?php

namespace MohammadAlavi\Laragen\PathParameters;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\PathParameter;

use function Safe\preg_match;
use function Safe\preg_match_all;

final readonly class PathParameterAnalyzer
{
    private const UUID_PATTERN = '[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}';
    private const ALPHA_PATTERN = '[a-zA-Z]+';
    private const ALPHA_NUMERIC_PATTERN = '[a-zA-Z0-9]+';
    private const ULID_PATTERN = '[0-7][0-9a-hjkmnp-tv-zA-HJKMNP-TV-Z]{25}';

    /** @var string[] */
    private const INTEGER_PATTERNS = ['[0-9]+', '\d+', '[0-9]*'];

    /**
     * Analyze a route and return enriched path Parameter objects.
     *
     * @return Parameter[]
     */
    public function analyze(Route $route): array
    {
        $uri = $route->uri();
        preg_match_all('/{(.*?)}/', $uri, $matches);

        /** @var string[] $segments */
        $segments = $matches[1];

        if ([] === $segments) {
            return [];
        }

        $parameters = [];

        foreach ($segments as $segment) {
            $isOptional = Str::endsWith($segment, '?');
            $name = Str::replaceLast('?', '', $segment);
            $schema = $this->resolveSchema($route, $name);

            $param = Parameter::path($name, PathParameter::create($schema));

            if (!$isOptional) {
                $param = $param->required();
            }

            $parameters[] = $param;
        }

        return $parameters;
    }

    private function resolveSchema(Route $route, string $name): JSONSchema
    {
        /** @var string|null $pattern */
        $pattern = $route->wheres[$name] ?? null;

        if (null === $pattern) {
            return $this->resolveFromModelBinding($route, $name) ?? Schema::string();
        }

        if ($this->isIntegerPattern($pattern)) {
            return Schema::integer();
        }

        if (self::UUID_PATTERN === $pattern) {
            return Schema::string()->format(StringFormat::UUID);
        }

        if (self::ALPHA_PATTERN === $pattern) {
            return Schema::string()->pattern(self::ALPHA_PATTERN);
        }

        if (self::ALPHA_NUMERIC_PATTERN === $pattern) {
            return Schema::string()->pattern(self::ALPHA_NUMERIC_PATTERN);
        }

        if (self::ULID_PATTERN === $pattern) {
            return Schema::string()->pattern(self::ULID_PATTERN);
        }

        if ($this->isEnumPattern($pattern)) {
            return Schema::enum(...explode('|', $pattern));
        }

        // Unknown pattern — keep as string with the regex as pattern
        return Schema::string()->pattern($pattern);
    }

    private function resolveFromModelBinding(Route $route, string $name): JSONSchema|null
    {
        /** @var string|null $uses */
        $uses = $route->getAction()['uses'] ?? null;

        if (!is_string($uses) || !str_contains($uses, '@')) {
            return null;
        }

        [$controllerClass, $method] = explode('@', $uses, 2);

        if (!class_exists($controllerClass) || !method_exists($controllerClass, $method)) {
            return null;
        }

        $reflection = new \ReflectionMethod($controllerClass, $method);

        foreach ($reflection->getParameters() as $parameter) {
            if ($parameter->getName() !== $name) {
                continue;
            }

            $type = $parameter->getType();

            if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                return null;
            }

            /** @var class-string $typeName */
            $typeName = $type->getName();

            if (!is_subclass_of($typeName, \Illuminate\Database\Eloquent\Model::class)) {
                return null;
            }

            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = new $typeName();

            return match ($model->getKeyType()) {
                'int', 'integer' => Schema::integer(),
                default => Schema::string(),
            };
        }

        return null;
    }

    private function isIntegerPattern(string $pattern): bool
    {
        return in_array($pattern, self::INTEGER_PATTERNS, true);
    }

    /**
     * Check if the pattern looks like a pipe-separated enum (e.g., "user|admin|guest").
     * Must be simple word values separated by pipes, with no regex metacharacters.
     */
    private function isEnumPattern(string $pattern): bool
    {
        if (!str_contains($pattern, '|')) {
            return false;
        }

        foreach (explode('|', $pattern) as $value) {
            if ('' === $value || 1 !== preg_match('/^[\w\-]+$/', $value)) {
                return false;
            }
        }

        return true;
    }
}
