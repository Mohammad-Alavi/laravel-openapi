<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * HTTP Status Code for Response.
 *
 * Represents an HTTP status code (1XX-5XX) used as a key in the Responses
 * Object to specify expected responses for an operation.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#responses-object
 */
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

    public static function resetContent(): self
    {
        return new self('205');
    }

    public static function partialContent(): self
    {
        return new self('206');
    }

    public static function movedPermanently(): self
    {
        return new self('301');
    }

    public static function found(): self
    {
        return new self('302');
    }

    public static function movedTemporarily(): self
    {
        return new self('302');
    }

    public static function seeOther(): self
    {
        return new self('303');
    }

    public static function notModified(): self
    {
        return new self('304');
    }

    public static function temporaryRedirect(): self
    {
        return new self('307');
    }

    public static function permanentRedirect(): self
    {
        return new self('308');
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

    public static function methodNotAllowed(): self
    {
        return new self('405');
    }

    public static function notAcceptable(): self
    {
        return new self('406');
    }

    public static function requestTimeout(): self
    {
        return new self('408');
    }

    public static function conflict(): self
    {
        return new self('409');
    }

    public static function gone(): self
    {
        return new self('410');
    }

    public static function unsupportedMediaType(): self
    {
        return new self('415');
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

    public static function notImplemented(): self
    {
        return new self('501');
    }

    public static function badGateway(): self
    {
        return new self('502');
    }

    public static function serviceUnavailable(): self
    {
        return new self('503');
    }

    public static function gatewayTimeout(): self
    {
        return new self('504');
    }

    public function value(): string
    {
        return $this->value;
    }
}
