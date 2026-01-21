<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use Webmozart\Assert\Assert;

/**
 * Request Body Object.
 *
 * Describes a single request body. The content field is required and describes
 * the content of the request body using media type objects.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#request-body-object
 */
final class RequestBody extends ExtensibleObject
{
    private Description|null $description = null;
    private true|null $required = null;
    private Content $content;

    private function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public static function create(ContentEntry ...$contentEntry): self
    {
        Assert::minCount($contentEntry, 1, 'RequestBody content is required per OpenAPI spec.');

        return new self(Content::create(...$contentEntry));
    }

    public function required(): self
    {
        $clone = clone $this;

        $clone->required = true;

        return $clone;
    }

    public function content(ContentEntry ...$contentEntry): self
    {
        $clone = clone $this;

        $clone->content = Content::create(...$contentEntry);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            'content' => $this->content,
            'required' => $this->required,
        ]);
    }
}
