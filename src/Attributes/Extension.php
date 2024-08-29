<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\LaravelOpenApi\Factories\ExtensionFactory;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
class Extension
{
    public string|null $factory;
    public string|null $key;
    public string|null $value;

    public function __construct(string|null $factory = null, string|null $key = null, string|null $value = null)
    {
        if ($factory) {
            $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Extensions\\' . $factory;

            if (!is_a($this->factory, ExtensionFactory::class, true)) {
                throw new \InvalidArgumentException('Factory class must be an instance of ExtensionFactory');
            }
        }

        $this->factory ??= null;
        $this->key = $key;
        $this->value = $value;
    }
}
