<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Headers\Headers;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links\LinkEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links\Links;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

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

    protected function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            'headers' => $this->headers,
            'content' => $this->content,
            'links' => $this->links,
        ]);
    }
}
