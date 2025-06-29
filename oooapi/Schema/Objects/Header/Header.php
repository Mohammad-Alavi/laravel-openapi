<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;

final class Header extends ExtensibleObject
{
    private Description|null $description = null;
    private true|null $required = null;
    private true|null $deprecated = null;
    private string|null $style = null;
    private bool|null $explode = null;
    private JSONSchema|null $schema = null;
    private Examples|null $examples = null;
    private Content|null $content = null;

    public function description(Description|null $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function required(): self
    {
        $clone = clone $this;

        $clone->required = true;

        return $clone;
    }

    public function deprecated(): self
    {
        $clone = clone $this;

        $clone->deprecated = true;

        return $clone;
    }

    public function style(string|null $style): self
    {
        $clone = clone $this;

        $clone->style = $style;

        return $clone;
    }

    public function explode(bool|null $explode = true): self
    {
        $clone = clone $this;

        $clone->explode = $explode;

        return $clone;
    }

    public function schema(JSONSchema|null $schema): self
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    public function examples(ExampleEntry ...$exampleEntry): self
    {
        $clone = clone $this;

        $clone->examples = Examples::create(...$exampleEntry);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
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
            'required' => $this->required,
            'deprecated' => $this->deprecated,
            'style' => $this->style,
            'explode' => $this->explode,
            'schema' => $this->schema,
            'examples' => $this->examples,
            'content' => $this->content,
        ]);
    }
}
