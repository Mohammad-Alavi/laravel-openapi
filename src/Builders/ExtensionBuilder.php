<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension as ExtensionAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ExtensionFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;

final readonly class ExtensionBuilder
{
    /**
     * @template T of ExtensibleObject
     *
     * @param T $extensibleObject
     *
     * @return T
     */
    public function build(ExtensibleObject $extensibleObject, Collection $attributes): ExtensibleObject
    {
        return $attributes->reduce(
            static function (ExtensibleObject $object, ExtensionAttribute $extensionAttribute): ExtensibleObject {
                if (is_a($extensionAttribute->factory, ExtensionFactory::class, true)) {
                    /** @var ExtensionFactory $factory */
                    $factory = app($extensionAttribute->factory);
                    $key = $factory->key();
                    $value = $factory->value();
                } else {
                    $key = $extensionAttribute->key;
                    $value = $extensionAttribute->value;
                }

                return $object->addExtension(Extension::create($key, $value));
            },
            $extensibleObject,
        );
    }
}
