<?php

declare(strict_types=1);

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style;

/**
 * Marker interface for styles applicable to query parameters.
 *
 * Query parameters support allowReserved which determines whether
 * reserved characters should be percent-encoded.
 *
 * Valid query styles: form, spaceDelimited, pipeDelimited, deepObject
 *
 * @see https://spec.openapis.org/oas/v3.1.1#parameter-object
 */
interface QueryApplicable
{
    /**
     * When true, allows reserved characters to pass through without percent-encoding.
     *
     * Reserved characters as defined by RFC3986: :/?#[]@!$&'()*+,;=
     * This property only applies to parameters with an `in` value of `query`.
     */
    public function allowReserved(): self;
}
