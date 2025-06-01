<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Parameter extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    public const IN_QUERY = 'query';
    public const IN_HEADER = 'header';
    public const IN_PATH = 'path';
    public const IN_COOKIE = 'cookie';
    public const STYLE_MATRIX = 'matrix';
    public const STYLE_LABEL = 'label';
    public const STYLE_FORM = 'form';
    public const STYLE_SIMPLE = 'simple';
    public const STYLE_SPACE_DELIMITED = 'spaceDelimited';
    public const STYLE_PIPE_DELIMITED = 'pipeDelimited';
    public const STYLE_DEEP_OBJECT = 'deepObject';

    public string|null $name = null;
    public bool|null $required = null;
    protected string|null $in = null;
    protected string|null $description = null;
    protected bool|null $deprecated = null;
    protected bool|null $allowEmptyValue = null;
    protected string|null $style = null;
    protected bool|null $explode = null;
    protected bool|null $allowReserved = null;
    public JSONSchema|null $schema = null;
    protected mixed $example = null;

    /** @var Example[]|null */
    protected array|null $examples = null;

    /** @var MediaType[]|null */
    protected array|null $content = null;

    public static function query(): static
    {
        return static::create()->in(static::IN_QUERY);
    }

    public function in(string|null $in): static
    {
        $clone = clone $this;

        $clone->in = $in;

        return $clone;
    }

    public static function header(): static
    {
        return static::create()->in(static::IN_HEADER);
    }

    public static function path(): static
    {
        return static::create()->in(static::IN_PATH);
    }

    public static function cookie(): static
    {
        return static::create()->in(static::IN_COOKIE);
    }

    public function name(string|null $name): static
    {
        $clone = clone $this;

        $clone->name = $name;

        return $clone;
    }

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

    public function schema(JSONSchema|null $schema): static
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
            'name' => $this->name,
            'in' => $this->in,
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
