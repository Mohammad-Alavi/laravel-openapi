<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\ParameterLocation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

final readonly class In extends StringField
{
    private function __construct(
        private ParameterLocation $location,
    ) {
    }

    public static function path(): self
    {
        return new self(ParameterLocation::PATH);
    }

    public static function query(): self
    {
        return new self(ParameterLocation::QUERY);
    }

    public static function header(): self
    {
        return new self(ParameterLocation::HEADER);
    }

    public static function cookie(): self
    {
        return new self(ParameterLocation::COOKIE);
    }

    public static function querystring(): self
    {
        return new self(ParameterLocation::QUERYSTRING);
    }

    public function value(): string
    {
        return $this->location->value;
    }
}
