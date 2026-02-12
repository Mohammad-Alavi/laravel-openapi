<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style;

/**
 * Base class for styles that support the explode modifier.
 *
 * When omitted from the output, OAS consumers apply spec defaults:
 * - form, cookie: assumes explode is true
 * - all others: assumes explode is false
 *
 * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
 */
abstract class Explodable extends Base
{
    private bool|null $explode = null;

    /**
     * When true, generates separate parameters for each value of array or object.
     */
    final public function explode(bool $explode = true): static
    {
        $clone = clone $this;

        $clone->explode = $explode;

        return $clone;
    }

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'explode' => $this->explode,
        ];
    }
}
