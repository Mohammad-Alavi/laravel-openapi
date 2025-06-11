<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use Webmozart\Assert\Assert;

final readonly class Path
{
    private string $path;
    private PathItem $pathItem;

    public static function create(string $path, PathItem $pathItem): self
    {
        Assert::startsWith($path, '/');

        $instance = new self();
        $instance->path = $path;
        $instance->pathItem = $pathItem;

        return $instance;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function pathItem(): PathItem
    {
        return $this->pathItem;
    }
}
