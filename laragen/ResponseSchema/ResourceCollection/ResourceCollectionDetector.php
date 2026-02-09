<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\ResourceCollection;

use Illuminate\Http\Resources\Json\ResourceCollection;
use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;

final readonly class ResourceCollectionDetector implements ResponseDetector
{
    /**
     * Detect if a controller method returns a ResourceCollection subclass.
     *
     * @param class-string $controllerClass
     *
     * @return class-string<ResourceCollection>|null
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

        if (!is_subclass_of($typeName, ResourceCollection::class)) {
            return null;
        }

        /* @var class-string<ResourceCollection> $typeName */
        return $typeName;
    }
}
