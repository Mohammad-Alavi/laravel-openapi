<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;

final readonly class Variables implements \JsonSerializable
{
    private function __construct(
        private array $variables,
    ) {
    }

    public static function create(Variable ...$map): self
    {
        return new self($map);
    }

    /**
     * @return array<string, ServerVariable>|null $variables
     */
    public function jsonSerialize(): array|null
    {
        if (empty($this->variables)) {
            return null;
        }

        return array_reduce(
            $this->variables,
            static function (array $carry, Variable $variableMap) {
                return array_merge($carry, $variableMap->value());
            },
            [],
        );
    }
}
