<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\SpatieData;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;
use Spatie\LaravelData\Data;

final readonly class SpatieDataRequestDetector implements RequestDetector
{
    /**
     * Detect if a controller method has a Spatie Data subclass as a parameter.
     *
     * @param class-string $controllerClass
     *
     * @return class-string<Data>|null
     */
    public function detect(Route $route, string $controllerClass, string $method): string|null
    {
        if (!class_exists(Data::class)) {
            return null;
        }

        if (!method_exists($controllerClass, $method)) {
            return null;
        }

        $reflection = new \ReflectionMethod($controllerClass, $method);

        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();

            if (!$type instanceof \ReflectionNamedType) {
                continue;
            }

            $typeName = $type->getName();

            if (is_subclass_of($typeName, Data::class)) {
                /** @var class-string<Data> $typeName */
                return $typeName;
            }
        }

        return null;
    }
}
