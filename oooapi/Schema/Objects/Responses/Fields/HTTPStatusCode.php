<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

final readonly class HTTPStatusCode extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::regex($value, '/^[1-5]\d{2}$/');
    }

    public static function create(string $statusCode): self
    {
        return new self($statusCode);
    }

    public static function ok(): self
    {
        return new self('200');
    }

    public static function created(): self
    {
        return new self('201');
    }

    public static function accepted(): self
    {
        return new self('202');
    }

    public static function noContent(): self
    {
        return new self('204');
    }

    public static function movedPermanently(): self
    {
        return new self('301');
    }

    public static function movedTemporarily(): self
    {
        return new self('302');
    }

    public static function badRequest(): self
    {
        return new self('400');
    }

    public static function unauthorized(): self
    {
        return new self('401');
    }

    public static function forbidden(): self
    {
        return new self('403');
    }

    public static function notFound(): self
    {
        return new self('404');
    }

    public static function unprocessableEntity(): self
    {
        return new self('422');
    }

    public static function tooManyRequests(): self
    {
        return new self('429');
    }

    public static function internalServerError(): self
    {
        return new self('500');
    }

    public function value(): string
    {
        return $this->value;
    }
}
