<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension as ExtensionAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ExtensionFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;

// TODO: refactor this class to use the ExtensionFactory interface
final readonly class ExtensionBuilder
{
    public function build(ExtensibleObject $extensibleObject, Collection $attributes): void
    {
        $attributes->each(static function (ExtensionAttribute $extensionAttribute) use ($extensibleObject): void {
            if (is_a($extensionAttribute->factory, ExtensionFactory::class, true)) {
                /** @var ExtensionFactory $factory */
                $factory = app($extensionAttribute->factory);
                $key = $factory->key();
                $value = $factory->value();
            } else {
                $key = $extensionAttribute->key;
                $value = $extensionAttribute->value;
            }

            $extensibleObject->addExtension(Extension::create($key, $value));
        });
    }
}
