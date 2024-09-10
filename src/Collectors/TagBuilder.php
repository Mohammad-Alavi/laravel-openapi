<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors;

use MohammadAlavi\ObjectOrientedOAS\Objects\Tag;
use MohammadAlavi\LaravelOpenApi\Factories\TagFactory;
use MohammadAlavi\LaravelOpenApi\Helpers\BuilderHelper;

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
            ->filter(static fn ($tag) => is_a($tag, TagFactory::class, true))
            ->map(static function (string $tagFactory): Tag {
                /** @var TagFactory $tagFactoryInstance */
                $tagFactoryInstance = app($tagFactory);
                $tag = $tagFactoryInstance->build();

                throw_if(BuilderHelper::hasInvalidField($tag->toArray(), 'name'), new \InvalidArgumentException('Tag name is required.'));

                return $tag;
            })
            ->toArray();
    }
}
