<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\Headers;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links\LinkEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links\Links;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;

/**
 * Response Object.
 *
 * Describes a single response from an API Operation, including design-time,
 * static links to operations based on the response. The description field
 * is required.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#response-object
 */
final class Response extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;
    private Headers|null $headers = null;
    private Content|null $content = null;
    private Links|null $links = null;

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    public function headers(HeaderEntry ...$headerEntry): self
    {
        $clone = clone $this;

        $clone->headers = Headers::create(...$headerEntry);

        return $clone;
    }

    public function content(ContentEntry ...$contentEntry): self
    {
        $clone = clone $this;

        $clone->content = Content::create(...$contentEntry);

        return $clone;
    }

    public function links(LinkEntry ...$linkEntry): self
    {
        $clone = clone $this;

        $clone->links = Links::create(...$linkEntry);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            'summary' => $this->summary,
            'headers' => $this->headers,
            'content' => $this->content,
            'links' => $this->links,
        ]);
    }
}
