<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

readonly class TagBuilder
{
    public function __construct(
        private \MohammadAlavi\LaravelOpenApi\Builders\TagBuilder $tagBuilder,
    ) {
    }

    /** @return Tag[] */
    public function build(array $tagFactories): array
    {
        return $this->tagBuilder->build($tagFactories);
    }
}
