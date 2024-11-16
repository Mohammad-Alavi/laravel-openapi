<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Keyword;
use Webmozart\Assert\Assert;

final readonly class Type implements Keyword
{
    /** @param string|array<array-key, string> $type */
    private function __construct(
        private string|array $type,
    ) {
    }

    public static function create(self|string ...$type): self
    {
        $types = array_map(
            static fn (self|string $type) => is_string($type) ? $type : $type->value(),
            $type,
        );
        $validTypes = [
            'null',
            'boolean',
            'string',
            'integer',
            'number',
            'object',
            'array',
        ];
        Assert::allInArray(
            $types,
            $validTypes,
        );
        Assert::uniqueValues($types);

        return new self($types);
    }

    /** @return string|array<array-key, string> */
    public function value(): string|array
    {
        if (is_array($this->type) && 1 === count($this->type)) {
            return $this->type[0];
        }

        return $this->type;
    }

    /**
     * The JSON null constant.
     */
    public static function null(): self
    {
        return new self('null');
    }

    /**
     * The JSON true or false constants.
     */
    public static function boolean(): self
    {
        return new self('boolean');
    }

    /**
     * A JSON string.
     */
    public static function string(): self
    {
        return new self('string');
    }

    /**
     * A JSON number that represents an integer.
     */
    public static function integer(): self
    {
        return new self('integer');
    }

    /**
     * A JSON number.
     */
    public static function number(): self
    {
        return new self('number');
    }

    /**
     * A JSON object.
     */
    public static function object(): self
    {
        return new self('object');
    }

    /**
     * A JSON array.
     */
    public static function array(): self
    {
        return new self('array');
    }

    public static function name(): string
    {
        return 'type';
    }

    public function equals(self|string $type): bool
    {
        if ($type instanceof self) {
            return $this->type === $type->type;
        }

        return $this->type === $type;
    }
}
