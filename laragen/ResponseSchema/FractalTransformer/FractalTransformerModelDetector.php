<?php

namespace MohammadAlavi\Laragen\ResponseSchema\FractalTransformer;

use Illuminate\Database\Eloquent\Model;

final readonly class FractalTransformerModelDetector
{
    /**
     * Resolve the Model class from a Transformer's transform() method first parameter type.
     *
     * @param class-string $transformerClass
     *
     * @return class-string<Model>|null
     */
    public function detect(string $transformerClass): string|null
    {
        if (!method_exists($transformerClass, 'transform')) {
            return null;
        }

        $reflection = new \ReflectionMethod($transformerClass, 'transform');
        $params = $reflection->getParameters();

        if ([] === $params) {
            return null;
        }

        $firstParam = $params[0];
        $type = $firstParam->getType();

        if (!$type instanceof \ReflectionNamedType) {
            return null;
        }

        $typeName = $type->getName();

        if (!is_subclass_of($typeName, Model::class)) {
            return null;
        }

        /* @var class-string<Model> $typeName */
        return $typeName;
    }
}
