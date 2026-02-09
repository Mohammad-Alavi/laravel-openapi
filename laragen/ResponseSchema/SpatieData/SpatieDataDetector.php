<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\SpatieData;

use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;
use Spatie\LaravelData\Data;

final readonly class SpatieDataDetector implements ResponseDetector
{
    /**
     * Detect if a controller method returns a Spatie Data subclass.
     *
     * @param class-string $controllerClass
     *
     * @return class-string<Data>|null
     */
    public function detect(string $controllerClass, string $method): mixed
    {
        if (!class_exists(Data::class)) {
            return null;
        }

        if (!method_exists($controllerClass, $method)) {
            return null;
        }

        $reflection = new \ReflectionMethod($controllerClass, $method);
        $returnType = $reflection->getReturnType();

        if (!$returnType instanceof \ReflectionNamedType) {
            return null;
        }

        $typeName = $returnType->getName();

        if (!is_subclass_of($typeName, Data::class)) {
            return null;
        }

        /* @var class-string<Data> $typeName */
        return $typeName;
    }
}
