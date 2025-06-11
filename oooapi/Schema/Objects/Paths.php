<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Paths extends ExtensibleObject
{
    /** @var Path[] */
    private array $paths;

    public static function create(Path ...$path): self
    {
        $instance = new self();
        $instance->paths = $path;

        return $instance;
    }

    protected function toArray(): array
    {
        $paths = [];
        foreach ($this->paths ?? [] as $path) {
            $paths[$path->path()] = $path->pathItem();
        }

        return Arr::filter($paths);
    }
}
