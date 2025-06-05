<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter;

use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\AllowEmptyValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Deprecated;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Required;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Location;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class Parameter extends ExtensibleObject
{
    public const STYLE_FORM = 'form';
    public const STYLE_SIMPLE = 'simple';
    public JSONSchema|null $schema = null;

    private Required|null $required = null;
    private Description|null $description = null;
    private Deprecated|null $deprecated = null;
    private AllowEmptyValue|null $allowEmptyValue = null;
    private string|null $style = null;
    private bool|null $explode = null;
    private bool|null $allowReserved = null;
    private mixed $example = null;
    /** @var Example[]|null */
    private array|null $examples = null;
    /** @var MediaType[]|null */
    private array|null $content = null;

    private function __construct(
        private readonly Name $name,
        private readonly Location $in,
    ) {
    }

    public static function create(Name $name, Location $in): self
    {
        return new self($name, $in);
    }

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

    public function allowEmptyValue(): self
    {
        $clone = clone $this;

        $clone->allowEmptyValue = AllowEmptyValue::yes();

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

    public function allowReserved(bool|null $allowReserved = true): self
    {
        $clone = clone $this;

        $clone->allowReserved = $allowReserved;

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
