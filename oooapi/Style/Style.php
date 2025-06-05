<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Style;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Cookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Query;

final readonly class Style implements \JsonSerializable
{
    private function __construct(
        private string $style,
        private Path|Header|Query|Cookie $in,
        private mixed $value,
    ) {
    }

    public static function matrix(object|array|string|float|int|bool|null $value): self
    {
        return new self('matrix', In::path(), $value);
    }

    public static function label(object|array|string|float|int|bool|null $value): self
    {
        return new self('label', In::path(), $value);
    }

    public static function simple(object|array|string|float|int|bool|null $value, Path|Header $in): self
    {
        return new self('simple', $in, $value);
    }

    public static function form(object|array|string|float|int|bool|null $value, Query|Cookie $in): self
    {
        return new self('form', $in, $value);
    }

    public static function spaceDelimited(object|array $value): self
    {
        return new self('spaceDelimited', In::query(), $value);
    }

    public static function pipeDelimited(object|array $value): self
    {
        return new self('pipeDelimited', In::query(), $value);
    }

    public static function deepObject(object $value): self
    {
        return new self('deepObject', In::query(), $value);
    }

    public function jsonSerialize(): array
    {
        return [
            'style' => $this->style,
            'in' => $this->in->value(),
            'value' => $this->value,
        ];
    }
}
