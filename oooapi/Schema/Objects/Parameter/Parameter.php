<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\AllowEmptyValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\Deprecated;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\In\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\Required;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Location\Location;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Label;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Matrix;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Simple;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\SpaceDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\ContentRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Cookie\SchemaCookieRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Header\SchemaHeaderRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Path\SchemaPathRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Query\SchemaQueryRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use Webmozart\Assert\Assert;

final class Parameter extends ExtensibleObject implements ParameterBuilder
{
    private JSONSchema|null $schema = null;

    private Required|null $required = null;
    private Description|null $description = null;
    private Deprecated|null $deprecated = null;
    private AllowEmptyValue|null $allowEmptyValue = null;
    private DeepObject|Form|Label|Matrix|PipeDelimited|Simple|SpaceDelimited|null $style = null;
    private Example|null $example = null;
    /** @var Example[]|null */
    private array|null $examples = null;
    /** @var MediaType[]|null */
    private array|null $content = null;

    private function __construct(
        private readonly Name $name,
        private readonly Location $in,
    ) {
    }

    public static function cookie(Name $name): ContentRule&SchemaCookieRule
    {
        return new self($name, In::cookie());
    }

    public static function header(Name $name): ContentRule&SchemaHeaderRule
    {
        return new self($name, In::header());
    }

    public static function path(Name $name): ContentRule&SchemaPathRule
    {
        return new self($name, In::path());
    }

    public static function query(Name $name): ContentRule&SchemaQueryRule
    {
        return new self($name, In::query());
    }

    public function schema(JSONSchema $schema): static
    {
        Assert::null($this->content, 'Parameter object cannot have both content and schema fields.');

        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    public function content(MediaType ...$mediaType): CommonFields
    {
        Assert::null($this->schema, 'Parameter object cannot have both content and schema fields.');

        $clone = clone $this;

        $clone->content = [] !== $mediaType ? $mediaType : null;

        return $clone;
    }

    public function description(Description|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function required(): static
    {
        $clone = clone $this;

        $clone->required = Required::yes();

        return $clone;
    }

    public function deprecated(): static
    {
        $clone = clone $this;

        $clone->deprecated = Deprecated::yes();

        return $clone;
    }

    public function allowEmptyValue(): static
    {
        $clone = clone $this;

        $clone->allowEmptyValue = AllowEmptyValue::yes();

        return $clone;
    }

    public function style(DeepObject|Form|Label|Matrix|PipeDelimited|Simple|SpaceDelimited $style): static
    {
        $clone = clone $this;

        $clone->style = $style;

        return $clone;
    }

    public function example(Example $example): static
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

    protected function toArray(): array
    {
        Assert::notNull(
            $this->content ?? $this->schema,
            'Parameter object must have either a content or a schema field.',
        );

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
            ...($this->style?->toArray() ?? []),
            'schema' => $this->schema,
            'example' => $this->example,
            'examples' => [] !== $examples ? $examples : null,
            'content' => [] !== $content ? $content : null,
        ]);
    }
}
