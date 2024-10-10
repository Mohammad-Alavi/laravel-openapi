<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\HasKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use Webmozart\Assert\Assert;

class Response extends ExtensibleObject implements HasKey
{
    protected readonly int|string $statusCode;
    protected readonly string $description;

    /** @var Header[]|null */
    protected array|null $headers = null;

    /** @var MediaType[]|null */
    protected array|null $content = null;

    /** @var Link[]|null */
    protected array|null $links = null;

    public static function default(string $description = 'Default Response'): static
    {
        return static::create('default', $description);
    }

    final public static function create(int|string $statusCode, string $description): static
    {
        Assert::regex((string) $statusCode, '/^[1-5]\d{2}$/');

        $instance = new static();

        $instance->statusCode = $statusCode;
        $instance->description = $description;

        return $instance;
    }

    public static function ok(string $description = 'OK'): static
    {
        return static::create(200, $description);
    }

    public static function created(string $description = 'Created'): static
    {
        return static::create(201, $description);
    }

    public static function accepted(string $description = 'Accepted'): static
    {
        return static::create(202, $description);
    }

    public static function deleted(string $description = 'Deleted'): static
    {
        return static::create(204, $description);
    }

    public static function movedPermanently(string $description = 'Moved Permanently'): static
    {
        return static::create(301, $description);
    }

    public static function movedTemporarily(string $description = 'Moved Temporarily'): static
    {
        return static::create(302, $description);
    }

    public static function badRequest(string $description = 'Bad Request'): static
    {
        return static::create(400, $description);
    }

    public static function unauthorized(string $description = 'Unauthorized'): static
    {
        return static::create(401, $description);
    }

    public static function forbidden(string $description = 'Forbidden'): static
    {
        return static::create(403, $description);
    }

    public static function notFound(string $description = 'Not Found'): static
    {
        return static::create(404, $description);
    }

    public static function unprocessableEntity(string $description = 'Unprocessable Entity'): static
    {
        return static::create(422, $description);
    }

    public static function tooManyRequests(string $description = 'Too Many Requests'): static
    {
        return static::create(429, $description);
    }

    public static function internalServerError(string $description = 'Internal Server Error'): static
    {
        return static::create(500, $description);
    }

    public function headers(Header ...$header): static
    {
        $clone = clone $this;

        $clone->headers = [] !== $header ? $header : null;

        return $clone;
    }

    public function content(MediaType ...$mediaType): static
    {
        $clone = clone $this;

        $clone->content = [] !== $mediaType ? $mediaType : null;

        return $clone;
    }

    public function links(Link ...$link): static
    {
        $clone = clone $this;

        $clone->links = [] !== $link ? $link : null;

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

        $links = [];
        foreach ($this->links ?? [] as $link) {
            $links[$link->key()] = $link;
        }

        return Arr::filter([
            'description' => $this->description,
            'headers' => [] !== $headers ? $headers : null,
            'content' => [] !== $content ? $content : null,
            'links' => [] !== $links ? $links : null,
        ]);
    }

    final public function key(): string
    {
        return (string) $this->statusCode;
    }
}
