<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Deprecated;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Required;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Header extends ExtensibleObject
{
    private Description|null $description = null;
    private Required|null $required = null;
    private Deprecated|null $deprecated = null;
    private string|null $style = null;
    private bool|null $explode = null;
    private JSONSchema|null $schema = null;
    private mixed $example = null;
    /** @var Example[]|null */
    private array|null $examples = null;
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

    public function example(mixed $example): self
    {
        $clone = clone $this;

        $clone->example = $example;

        return $clone;
    }

    public function examples(Example ...$example): self
    {
        $clone = clone $this;

        $clone->examples = [] !== $example ? $example : null;

        return $clone;
    }

    public function content(ContentEntry ...$contentEntry): self
    {
        $clone = clone $this;

        $clone->content = Content::create(...$contentEntry);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    protected function toArray(): array
    {
        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->key()] = $example;
        }

        return Arr::filter([
            'description' => $this->description,
            'required' => $this->required,
            'deprecated' => $this->deprecated,
            'style' => $this->style,
            'explode' => $this->explode,
            'schema' => $this->schema,
            'example' => $this->example,
            'examples' => [] !== $examples ? $examples : null,
            'content' => $this->content,
        ]);
    }
}
