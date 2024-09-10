<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors\Paths\Operation;

use MohammadAlavi\ObjectOrientedOAS\Objects\Tag;

class TagBuilder
{
    public function __construct(
        private readonly \MohammadAlavi\LaravelOpenApi\Collectors\TagBuilder $tagBuilder,
    ) {
    }

    /**
     * @return Tag[]
     */
    public function build(array $tagFactories): array
    {
        return $this->tagBuilder->build($tagFactories);
    }
}
