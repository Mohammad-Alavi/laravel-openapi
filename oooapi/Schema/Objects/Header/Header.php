<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Deprecated;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Required;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class Header extends ExtensibleObject
{
    private readonly string $key;

    public static function create(string $key): self
    {
        $static = new self();

        $static->key = $key;

        return $static;
    }

    public function key(): string
    {
        return $this->key;
    }

    private Description|null $description = null;
    private Required|null $required = null;
    private Deprecated|null $deprecated = null;
    private string|null $style = null;
    private bool|null $explode = null;
    private JSONSchema|null $schema = null;
    private mixed $example = null;

    /** @var Example[]|null */
    private array|null $examples = null;

    /** @var MediaType[]|null */
    private array|null $content = null;

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

    public function content(MediaType ...$mediaType): self
    {
        $clone = clone $this;

        $clone->content = [] !== $mediaType ? $mediaType : null;

        return $clone;
    }

    protected function toArray(): array
    {
        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->key()] = $example;
        }

        $content = [];
        foreach ($this->content ?? [] as $contentItem) {
            $content[$contentItem->key()] = $contentItem;
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
            'content' => [] !== $content ? $content : null,
        ]);
    }
}
