<?php

declare(strict_types=1);

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\AllowReserved;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Explodable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\QueryApplicable;

/**
 * Pipe-delimited style serialization.
 *
 * Applicable locations: query only
 * Default explode: false
 *
 * Serialization behavior:
 * - Only supports arrays (not primitives or objects)
 * - array with explode=false: value1|value2|value3 (e.g., "blue|black|brown")
 * - With explode=true: behaves like form style
 *
 * Equivalent to collectionFormat: pipes in OpenAPI 2.0.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#style-values
 */
final class PipeDelimited extends Explodable implements QueryApplicable
{
    use AllowReserved;

    protected function value(): string
    {
        return 'pipeDelimited';
    }
}
