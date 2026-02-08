<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

use Illuminate\Http\Resources\Json\JsonResource;

final readonly class ResponseDetector
{
    /**
     * Detect if a controller method returns a JsonResource subclass.
     *
     * @param class-string $controllerClass
     *
     * @return class-string<JsonResource>|null
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

        if (!is_subclass_of($typeName, JsonResource::class)) {
            return null;
        }

        /* @var class-string<JsonResource> $typeName */
        return $typeName;
    }
}
