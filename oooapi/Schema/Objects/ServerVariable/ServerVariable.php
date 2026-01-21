<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\DefaultValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Enum;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use Webmozart\Assert\Assert;

/**
 * Server Variable Object.
 *
 * An object representing a Server Variable for server URL template substitution.
 * The default value is required. If enum is provided, the default value MUST
 * exist in the enum's values.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#server-variable-object
 */
final class ServerVariable extends ExtensibleObject
{
    private Enum|null $enum = null;
    private Description|null $description = null;

    private function __construct(
        private readonly DefaultValue $defaultValue,
    ) {
    }

    public function enum(string ...$enum): self
    {
        Assert::true(
            in_array($this->defaultValue->value(), $enum, true),
            'The default value must exist in the enum’s values.',
        );

        $clone = clone $this;

        $clone->enum = Enum::create(...$enum);

        return $clone;
    }

    public static function create(string $defaultValue): self
    {
        return new self(DefaultValue::create($defaultValue));
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'enum' => $this->enum,
            'default' => $this->defaultValue,
            'description' => $this->description,
        ]);
    }
}
