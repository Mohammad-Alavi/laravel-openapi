<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Descriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleKeyCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleKeyCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Header extends ExtensibleObject implements SimpleKeyCreator
{
    use SimpleKeyCreatorTrait;

    public const STYLE_MATRIX = 'matrix';
    public const STYLE_LABEL = 'label';
    public const STYLE_FORM = 'form';
    public const STYLE_SIMPLE = 'simple';
    public const STYLE_SPACE_DELIMITED = 'spaceDelimited';
    public const STYLE_PIPE_DELIMITED = 'pipeDelimited';
    public const STYLE_DEEP_OBJECT = 'deepObject';

    protected string|null $description = null;
    protected bool|null $required = null;
    protected bool|null $deprecated = null;
    protected bool|null $allowEmptyValue = null;
    protected string|null $style = null;
    protected bool|null $explode = null;
    protected bool|null $allowReserved = null;
    protected Descriptor|null $schema = null;
    protected mixed $example = null;

    /** @var Example[]|null */
    protected array|null $examples = null;

    /** @var MediaType[]|null */
    protected array|null $content = null;

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function required(bool|null $required = true): static
    {
        $clone = clone $this;

        $clone->required = $required;

        return $clone;
    }

    public function deprecated(bool|null $deprecated = true): static
    {
        $clone = clone $this;

        $clone->deprecated = $deprecated;

        return $clone;
    }

    public function allowEmptyValue(bool|null $allowEmptyValue = true): static
    {
        $clone = clone $this;

        $clone->allowEmptyValue = $allowEmptyValue;

        return $clone;
    }

    public function style(string|null $style): static
    {
        $clone = clone $this;

        $clone->style = $style;

        return $clone;
    }

    public function explode(bool|null $explode = true): static
    {
        $clone = clone $this;

        $clone->explode = $explode;

        return $clone;
    }

    public function allowReserved(bool|null $allowReserved = true): static
    {
        $clone = clone $this;

        $clone->allowReserved = $allowReserved;

        return $clone;
    }

    public function schema(Descriptor|null $schema): static
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    public function example(mixed $example): static
    {
        $clone = clone $this;

        $clone->example = $example;

        return $clone;
    }

    public function examples(Example ...$example): static
    {
        $clone = clone $this;

        $clone->examples = [] !== $example ? $example : null;

        return $clone;
    }

    public function content(MediaType ...$mediaType): static
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
            'allowEmptyValue' => $this->allowEmptyValue,
            'style' => $this->style,
            'explode' => $this->explode,
            'allowReserved' => $this->allowReserved,
            'schema' => $this->schema,
            'example' => $this->example,
            'examples' => [] !== $examples ? $examples : null,
            'content' => [] !== $content ? $content : null,
        ]);
    }
}
