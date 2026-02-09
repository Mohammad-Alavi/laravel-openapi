<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\EloquentModel;

use Illuminate\Database\Eloquent\Model;
use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;

final readonly class EloquentModelDetector implements ResponseDetector
{
    /**
     * Detect if a controller method returns an Eloquent Model subclass.
     *
     * @param class-string $controllerClass
     *
     * @return class-string<Model>|null
     */
    public function detect(string $controllerClass, string $method): mixed
    {
        if (!method_exists($controllerClass, $method)) {
            return null;
        }

        $reflection = new \ReflectionMethod($controllerClass, $method);
        $returnType = $reflection->getReturnType();

        if (!$returnType instanceof \ReflectionNamedType) {
            return null;
        }

        $typeName = $returnType->getName();

        if (!is_subclass_of($typeName, Model::class)) {
            return null;
        }

        /* @var class-string<Model> $typeName */
        return $typeName;
    }
}
