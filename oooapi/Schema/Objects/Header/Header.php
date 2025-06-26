<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Deprecated;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Required;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Examples\Examples;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Header extends ExtensibleObject
{
    private Description|null $description = null;
    private Required|null $required = null;
    private Deprecated|null $deprecated = null;
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

        $clone->required = Required::yes();

        return $clone;
    }

    public function deprecated(): self
    {
        $clone = clone $this;

        $clone->deprecated = Deprecated::yes();

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

    protected function toArray(): array
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
