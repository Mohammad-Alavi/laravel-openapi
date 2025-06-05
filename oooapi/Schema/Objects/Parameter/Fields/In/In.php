<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Cookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Query;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class In extends StringField implements Cookie, Header, Path, Query
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function path(): Path
    {
        return new self('path');
    }

    public static function query(): Query
    {
        return new self('query');
    }

    public static function header(): Header
    {
        return new self('header');
    }

    public static function cookie(): Cookie
    {
        return new self('cookie');
    }

    public function value(): string
    {
        return $this->value;
    }
}
