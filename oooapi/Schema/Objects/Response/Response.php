<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\HasKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links\Links;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use Webmozart\Assert\Assert;

final class Response extends ExtensibleObject implements HasKey
{
    private const DEFAULT = 'default';

    /** @var Header[]|null */
    private array|null $headers = null;

    /** @var MediaType[]|null */
    private array|null $content = null;

    private Links|null $links = null;

    private readonly int|string $statusCode;
    private readonly string $description;

    public static function default(string $description = 'Default Response'): self
    {
        return self::create(self::DEFAULT, $description);
    }

    final public static function create(int|string $statusCode, string $description): self
    {
        if (self::DEFAULT !== $statusCode) {
            Assert::regex((string) $statusCode, '/^[1-5]\d{2}$/');
        }

        $self = new self();

        $self->statusCode = $statusCode;
        $self->description = $description;

        return $self;
    }

    public static function ok(string $description = 'OK'): self
    {
        return self::create(200, $description);
    }

    public static function created(string $description = 'Created'): self
    {
        return self::create(201, $description);
    }

    public static function accepted(string $description = 'Accepted'): self
    {
        return self::create(202, $description);
    }

    public static function deleted(string $description = 'Deleted'): self
    {
        return self::create(204, $description);
    }

    public static function movedPermanently(string $description = 'Moved Permanently'): self
    {
        return self::create(301, $description);
    }

    public static function movedTemporarily(string $description = 'Moved Temporarily'): self
    {
        return self::create(302, $description);
    }

    public static function badRequest(string $description = 'Bad Request'): self
    {
        return self::create(400, $description);
    }

    public static function unauthorized(string $description = 'Unauthorized'): self
    {
        return self::create(401, $description);
    }

    public static function forbidden(string $description = 'Forbidden'): self
    {
        return self::create(403, $description);
    }

    public static function notFound(string $description = 'Not Found'): self
    {
        return self::create(404, $description);
    }

    public static function unprocessableEntity(string $description = 'Unprocessable Entity'): self
    {
        return self::create(422, $description);
    }

    public static function tooManyRequests(string $description = 'Too Many Requests'): self
    {
        return self::create(429, $description);
    }

    public static function internalServerError(string $description = 'Internal Server Error'): self
    {
        return self::create(500, $description);
    }

    public function statusCode(): int|string
    {
        return $this->statusCode;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function headers(Header ...$header): self
    {
        $clone = clone $this;

        $clone->headers = [] !== $header ? $header : null;

        return $clone;
    }

    public function content(MediaType ...$mediaType): self
    {
        $clone = clone $this;

        $clone->content = [] !== $mediaType ? $mediaType : null;

        return $clone;
    }

    public function links(Links $links): self
    {
        $clone = clone $this;

        $clone->links = $links;

        return $clone;
    }

    protected function toArray(): array
    {
        $headers = [];
        foreach ($this->headers ?? [] as $header) {
            $headers[$header->key()] = $header;
        }

        $content = [];
        foreach ($this->content ?? [] as $contentItem) {
            $content[$contentItem->key()] = $contentItem;
        }

        return Arr::filter([
            'description' => $this->description,
            'headers' => [] !== $headers ? $headers : null,
            'content' => [] !== $content ? $content : null,
            'links' => $this->links,
        ]);
    }

    final public function key(): string
    {
        return (string) $this->statusCode;
    }
}
