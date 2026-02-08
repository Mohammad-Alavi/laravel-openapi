<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\JsonResource;

use Illuminate\Http\Resources\Json\JsonResource as LaravelJsonResource;

final readonly class JsonResourceDetector
{
    /**
     * Detect if a controller method returns a JsonResource subclass.
     *
     * @param class-string $controllerClass
     *
     * @return class-string<LaravelJsonResource>|null
     */
    public function detect(string $controllerClass, string $method): string|null
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

        if (!is_subclass_of($typeName, LaravelJsonResource::class)) {
            return null;
        }

        /* @var class-string<LaravelJsonResource> $typeName */
        return $typeName;
    }
}
