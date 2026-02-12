<?php

namespace App\Domain\Documentation\Rendering\Services;

use App\Domain\Documentation\Rendering\DTOs\SpecPathData;
use App\Domain\Documentation\Rendering\DTOs\SpecTagData;

final class SpecParser
{
    /**
     * Extract tags from an OpenAPI spec.
     *
     * @param array<string, mixed> $spec
     * @return list<SpecTagData>
     */
    public function extractTags(array $spec): array
    {
        $tags = [];

        // Get declared tags
        foreach ($spec['tags'] ?? [] as $tag) {
            if (isset($tag['name'])) {
                $tags[$tag['name']] = new SpecTagData(
                    name: $tag['name'],
                    description: $tag['description'] ?? null,
                );
            }
        }

        // Discover tags from operations
        foreach ($spec['paths'] ?? [] as $methods) {
            foreach ($methods as $operation) {
                if (is_array($operation)) {
                    foreach ($operation['tags'] ?? [] as $tagName) {
                        if (! isset($tags[$tagName])) {
                            $tags[$tagName] = new SpecTagData(name: $tagName, description: null);
                        }
                    }
                }
            }
        }

        return array_values($tags);
    }

    /**
     * Extract paths from an OpenAPI spec.
     *
     * @param array<string, mixed> $spec
     * @return list<SpecPathData>
     */
    public function extractPaths(array $spec): array
    {
        $paths = [];

        foreach ($spec['paths'] ?? [] as $path => $methods) {
            $methodList = [];
            foreach ($methods as $method => $operation) {
                if (is_array($operation)) {
                    $methodList[] = strtoupper($method);
                }
            }

            $paths[] = new SpecPathData(
                path: $path,
                methods: $methodList,
            );
        }

        return $paths;
    }
}
