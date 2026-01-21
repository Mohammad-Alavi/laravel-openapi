<?php

declare(strict_types=1);

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style;

/**
 * Trait for styles that support allowReserved (query parameter styles only).
 *
 * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
 */
trait AllowReserved
{
    private true|null $allowReserved = null;

    /**
     * When true, allows reserved characters to pass through without percent-encoding.
     *
     * Reserved characters as defined by RFC3986: :/?#[]@!$&'()*+,;=
     * This property only applies to parameters with an `in` value of `query`.
     */
    public function allowReserved(): self
    {
        $clone = clone $this;

        $clone->allowReserved = true;

        return $clone;
    }

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'allowReserved' => $this->allowReserved,
        ];
    }
}
