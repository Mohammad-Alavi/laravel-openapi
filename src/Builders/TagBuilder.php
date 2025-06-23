<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class TagBuilder
{
    /**
     * @param array<array-key, class-string<Tag>> $tagFactories
     *
     * @return Tag[]
     */
    public function build(array $tagFactories): array
    {
        return collect($tagFactories)
            ->filter(static fn (string $tag): bool => is_a($tag, TagFactory::class, true))
            ->map(static function (string $tagFactory): Tag {
                /** @var TagFactory $tagFactoryInstance */
                $tagFactoryInstance = app($tagFactory);

                return $tagFactoryInstance->build();
            })->toArray();
    }
}
