<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ExtensionFactory;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
final readonly class Extension
{
    /** @var class-string<ExtensionFactory>|null */
    public string|null $factory;

    public function __construct(
        string|null $factory = null,
        public string|null $key = null,
        public string|null $value = null,
    ) {
        if (!is_null($factory) && '' !== $factory && '0' !== $factory) {
            $this->factory = class_exists($factory)
                ? $factory
                : app()->getNamespace() . 'OpenApi\\Extensions\\' . $factory;

            if (!is_a($this->factory, ExtensionFactory::class, true)) {
                throw new \InvalidArgumentException('Factory class must be an instance of ' . class_basename(ExtensionFactory::class));
            }
        }

        $this->factory ??= null;
    }
}
