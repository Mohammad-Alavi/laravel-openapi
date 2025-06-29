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

final class Response extends ExtensibleObject
{
    private Headers|null $headers = null;

    private Content|null $content = null;

    private Links|null $links = null;

    private function __construct(
        private readonly Description $description,
    ) {
    }

    public function headers(HeaderEntry ...$headerEntry): self
    {
        $clone = clone $this;

        $clone->headers = Headers::create(...$headerEntry);

        return $clone;
    }

    public static function create(Description $description): self
    {
        return new self($description);
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
            'headers' => $this->headers,
            'content' => $this->content,
            'links' => $this->links,
        ]);
    }
}
