<?php

namespace MohammadAlavi\Laragen\ResponseSchema\JsonResource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

use function Safe\file_get_contents;
use function Safe\preg_match;

final class JsonResourceModelDetector
{
    /**
     * Detect which Eloquent Model a JsonResource represents via @mixin DocBlock.
     *
     * @param class-string<JsonResource> $resourceClass
     *
     * @return class-string<Model>|null
     */
    public function detect(string $resourceClass): string|null
    {
        $reflection = new \ReflectionClass($resourceClass);
        $docComment = $reflection->getDocComment();

        if (false === $docComment) {
            return null;
        }

        if (0 === preg_match('/@mixin\s+([^\s]+)/', $docComment, $matches)) {
            return null;
        }

        $mixinClass = $matches[1];

        // Resolve fully qualified class name
        $resolved = $this->resolveClassName($mixinClass, $reflection);

        if (null === $resolved || !is_a($resolved, Model::class, true)) {
            return null;
        }

        return $resolved;
    }

    /**
     * @param \ReflectionClass<object> $context
     *
     * @return class-string|null
     */
    private function resolveClassName(string $className, \ReflectionClass $context): string|null
    {
        // Already fully qualified
        if (str_starts_with($className, '\\')) {
            $fqcn = ltrim($className, '\\');

            return class_exists($fqcn) ? $fqcn : null;
        }

        // Check use statements by reading the file source
        $fileName = $context->getFileName();

        if (false === $fileName) {
            return null;
        }

        $source = file_get_contents($fileName);

        // Look for a use statement that imports this class
        $pattern = '/^use\s+([^\s;]+\\\\' . preg_quote($className, '/') . ')\s*;/m';

        if (1 === preg_match($pattern, $source, $useMatches) && isset($useMatches[1])) {
            $fqcn = $useMatches[1];

            return class_exists($fqcn) ? $fqcn : null;
        }

        // Try same namespace
        $namespace = $context->getNamespaceName();
        $fqcn = $namespace . '\\' . $className;

        if (class_exists($fqcn)) {
            return $fqcn;
        }

        return null;
    }
}
