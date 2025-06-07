<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\InQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\QueryApplicable;

final class DeepObject extends Base implements QueryApplicable
{
    use InQuery;

    public function value(): string
    {
        return 'deepObject';
    }
}
