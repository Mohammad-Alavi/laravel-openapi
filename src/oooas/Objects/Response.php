<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property int|null $statusCode
 * @property string|null $description
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Header[]|null $headers
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[]|null $content
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Link[]|null $links
 */
class Response extends BaseObject
{
    /**
     * @var int|null
     */
    protected $statusCode;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Header[]|null
     */
    protected $headers;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[]|null
     */
    protected $content;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Link[]|null
     */
    protected $links;

    /**
     * @return static
     */
    public static function ok(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(200)
            ->description('OK');
    }

    /**
     * @return static
     */
    public static function created(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(201)
            ->description('Created');
    }

    /**
     * @return static
     */
    public static function movedPermanently(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(301)
            ->description('Moved Permanently');
    }

    /**
     * @return static
     */
    public static function movedTemporarily(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(302)
            ->description('Moved Temporarily');
    }

    /**
     * @return static
     */
    public static function badRequest(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(400)
            ->description('Bad Request');
    }

    /**
     * @return static
     */
    public static function unauthorized(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(401)
            ->description('Unauthorized');
    }

    /**
     * @return static
     */
    public static function forbidden(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(403)
            ->description('Forbidden');
    }

    /**
     * @return static
     */
    public static function notFound(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(404)
            ->description('Not Found');
    }

    /**
     * @return static
     */
    public static function unprocessableEntity(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(422)
            ->description('Unprocessable Entity');
    }

    /**
     * @return static
     */
    public static function tooManyRequests(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(429)
            ->description('Too Many Requests');
    }

    /**
     * @return static
     */
    public static function internalServerError(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->statusCode(500)
            ->description('Internal Server Error');
    }

    /**
     * @return static
     */
    public function statusCode(int|null $statusCode): self
    {
        $instance = clone $this;

        $instance->statusCode = $statusCode;

        return $instance;
    }

    /**
     * @return static
     */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Header[] $headers
     *
     * @return static
     */
    public function headers(Header ...$headers): self
    {
        $instance = clone $this;

        $instance->headers = $headers ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[] $content
     *
     * @return static
     */
    public function content(MediaType ...$content): self
    {
        $instance = clone $this;

        $instance->content = $content ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Link[] $links
     *
     * @return static
     */
    public function links(Link ...$links): self
    {
        $instance = clone $this;

        $instance->links = $links ?: null;

        return $instance;
    }

    protected function generate(): array
    {
        $headers = [];
        foreach ($this->headers ?? [] as $header) {
            $headers[$header->objectId] = $header;
        }

        $content = [];
        foreach ($this->content ?? [] as $contentItem) {
            $content[$contentItem->mediaType] = $contentItem;
        }

        $links = [];
        foreach ($this->links ?? [] as $link) {
            $links[$link->objectId] = $link;
        }

        return Arr::filter([
            'description' => $this->description,
            'headers' => $headers ?: null,
            'content' => $content ?: null,
            'links' => $links ?: null,
        ]);
    }
}
