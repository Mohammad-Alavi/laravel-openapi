<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;

final readonly class Http implements Scheme
{
    private function __construct(
        private string $scheme,
        private string|null $bearerFormat = null,
    ) {
    }

    public static function basic(): self
    {
        return new self('basic');
    }

    public static function bearer(string|null $bearerFormat = null): self
    {
        return new self('bearer', $bearerFormat);
    }

    public static function digest(): self
    {
        return new self('digest');
    }

    public static function dpop(): self
    {
        return new self('dpop');
    }

    public static function gnap(): self
    {
        return new self('gnap');
    }

    public static function hoba(): self
    {
        return new self('HOBA');
    }

    public static function mutual(): self
    {
        return new self('Mutual');
    }

    public static function negotiate(): self
    {
        return new self('Negotiate');
    }

    public static function oAuth(): self
    {
        return new self('OAuth');
    }

    public static function privateToken(): self
    {
        return new self('PrivateToken');
    }

    public static function scramSha1(): self
    {
        return new self('SCRAM-SHA-1');
    }

    public static function scramSha256(): self
    {
        return new self('SCRAM-SHA-256');
    }

    public static function vapid(): self
    {
        return new self('vapid');
    }

    public function type(): string
    {
        return 'http';
    }

    public function toArray(): array
    {
        return [
            'scheme' => $this->scheme,
            'bearerFormat' => $this->bearerFormat,
        ];
    }
}
