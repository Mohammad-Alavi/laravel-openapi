<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor as DescriptorContract;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012 as Dialect;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Def;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\Dependency;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocab;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocabulary;

class Descriptor implements DescriptorContract
{
    private Anchor|null $anchor = null;
    private Comment|null $comment = null;
    private Defs|null $defs = null;
    private DynamicAnchor|null $dynamicAnchor = null;
    private DynamicRef|null $dynamicRef = null;
    private ExclusiveMaximum|null $exclusiveMaximum = null;
    private ExclusiveMinimum|null $exclusiveMinimum = null;
    private Format|null $format = null;
    private Id|null $id = null;
    private Maximum|null $maximum = null;
    private MaxLength|null $maxLength = null;
    private Minimum|null $minimum = null;
    private MinLength|null $minLength = null;
    private MultipleOf|null $multipleOf = null;
    private Pattern|null $pattern = null;
    private Ref|null $ref = null;
    private Schema|null $schema = null;
    private Type|null $type = null;
    private Vocabulary|null $vocabulary = null;
    private UnevaluatedItems|null $unevaluatedItems = null;
    private UnevaluatedProperties|null $unevaluatedProperties = null;
    private MaxContains|null $maxContains = null;
    private MinContains|null $minContains = null;
    private MaxItems|null $maxItems = null;
    private MinItems|null $minItems = null;
    private UniqueItems|null $uniqueItems = null;
    private Items|null $items = null;
    private AllOf|null $allOf = null;
    private AnyOf|null $anyOf = null;
    private OneOf|null $oneOf = null;
    private AdditionalProperties|null $additionalProperties = null;
    private Properties|null $properties = null;
    private DependentRequired|null $dependentRequired = null;
    private MaxProperties|null $maxProperties = null;
    private MinProperties|null $minProperties = null;
    private Required|null $required = null;
    private DefaultValue|null $defaultValue = null;
    private Deprecated|null $deprecated = null;
    private Description|null $description = null;
    private Examples|null $examples = null;
    private IsReadOnly|null $isReadOnly = null;
    private IsWriteOnly|null $isWriteOnly = null;
    private Title|null $title = null;
    private Constant|null $constant = null;
    private Enum|null $enum = null;

    final private function __construct(string|null $schema = null)
    {
        $this->schema = is_null($schema) ? null : Dialect::schema($schema);
    }

    /**
     * Create a new instance of the Descriptor without any schema.
     */
    final public static function withoutSchema(): static
    {
        return new static(null);
    }

    /**
     * Create a new instance of the Descriptor with a schema.
     */
    final public static function create(string|null $schema = 'https://json-schema.org/draft-2020-12/schema'): static
    {
        return new static($schema);
    }

    final public function schema(string $uri): static
    {
        $clone = clone $this;

        $clone->schema = Dialect::schema($uri);

        return $clone;
    }

    final public function anchor(string $value): static
    {
        $clone = clone $this;

        $clone->anchor = Dialect::anchor($value);

        return $clone;
    }

    final public function comment(string $value): static
    {
        $clone = clone $this;

        $clone->comment = Dialect::comment($value);

        return $clone;
    }

    final public function defs(Def ...$def): static
    {
        $clone = clone $this;

        $clone->defs = Dialect::defs(...$def);

        return $clone;
    }

    final public function dynamicAnchor(string $value): static
    {
        $clone = clone $this;

        $clone->dynamicAnchor = Dialect::dynamicAnchor($value);

        return $clone;
    }

    final public function dynamicRef(string $uri): static
    {
        $clone = clone $this;

        $clone->dynamicRef = Dialect::dynamicRef($uri);

        return $clone;
    }

    final public function exclusiveMaximum(float $value): static
    {
        $clone = clone $this;

        $clone->exclusiveMaximum = Dialect::exclusiveMaximum($value);

        return $clone;
    }

    final public function exclusiveMinimum(float $value): static
    {
        $clone = clone $this;

        $clone->exclusiveMinimum = Dialect::exclusiveMinimum($value);

        return $clone;
    }

    final public function format(DefinedFormat $definedFormat): static
    {
        $clone = clone $this;

        $clone->format = Dialect::format($definedFormat);

        return $clone;
    }

    final public function id(string $uri): static
    {
        $clone = clone $this;

        $clone->id = Dialect::id($uri);

        return $clone;
    }

    final public function maximum(float $value): static
    {
        $clone = clone $this;

        $clone->maximum = Dialect::maximum($value);

        return $clone;
    }

    final public function maxLength(int $value): static
    {
        $clone = clone $this;

        $clone->maxLength = Dialect::maxLength($value);

        return $clone;
    }

    final public function minimum(float $value): static
    {
        $clone = clone $this;

        $clone->minimum = Dialect::minimum($value);

        return $clone;
    }

    final public function minLength(int $value): static
    {
        $clone = clone $this;

        $clone->minLength = Dialect::minLength($value);

        return $clone;
    }

    final public function multipleOf(float $value): static
    {
        $clone = clone $this;

        $clone->multipleOf = Dialect::multipleOf($value);

        return $clone;
    }

    final public function pattern(string $value): static
    {
        $clone = clone $this;

        $clone->pattern = Dialect::pattern($value);

        return $clone;
    }

    /**
     * Set a static reference to another <a href="https://json-schema.org/learn/glossary#schema">schema</a>.
     * This is useful for avoiding code duplication and promoting modularity when describing complex data structures.
     *
     * @see https://www.learnjsonschema.com/2020-12/core/ref/
     * @see https://json-schema.org/understanding-json-schema/structuring
     */
    final public function ref(string $uri): static
    {
        $clone = clone $this;

        $clone->ref = Dialect::ref($uri);

        return $clone;
    }

    final public function type(Type|string ...$type): static
    {
        $clone = clone $this;

        $clone->type = Dialect::type(...$type);

        return $clone;
    }

    final public function vocabulary(Vocab ...$vocab): static
    {
        $clone = clone $this;

        $clone->vocabulary = Dialect::vocabulary(...$vocab);

        return $clone;
    }

    final public function unevaluatedItems(DescriptorContract $descriptorContract): static
    {
        $clone = clone $this;

        $clone->unevaluatedItems = Dialect::unevaluatedItems($descriptorContract);

        return $clone;
    }

    final public function unevaluatedProperties(DescriptorContract $descriptorContract): static
    {
        $clone = clone $this;

        $clone->unevaluatedProperties = Dialect::unevaluatedProperties($descriptorContract);

        return $clone;
    }

    final public function maxContains(int $value): static
    {
        $clone = clone $this;

        $clone->maxContains = Dialect::maxContains($value);

        return $clone;
    }

    final public function minContains(int $value): static
    {
        $clone = clone $this;

        $clone->minContains = Dialect::minContains($value);

        return $clone;
    }

    final public function maxItems(int $value): static
    {
        $clone = clone $this;

        $clone->maxItems = Dialect::maxItems($value);

        return $clone;
    }

    final public function minItems(int $value): static
    {
        $clone = clone $this;

        $clone->minItems = Dialect::minItems($value);

        return $clone;
    }

    final public function uniqueItems(bool $value = true): static
    {
        $clone = clone $this;

        $clone->uniqueItems = Dialect::uniqueItems($value);

        return $clone;
    }

    final public function items(DescriptorContract $descriptorContract): static
    {
        $clone = clone $this;

        $clone->items = Dialect::items($descriptorContract);

        return $clone;
    }

    final public function allOf(DescriptorContract ...$builder): static
    {
        $clone = clone $this;

        $clone->allOf = Dialect::allOf(...$builder);

        return $clone;
    }

    final public function anyOf(DescriptorContract ...$builder): static
    {
        $clone = clone $this;

        $clone->anyOf = Dialect::anyOf(...$builder);

        return $clone;
    }

    final public function oneOf(DescriptorContract ...$builder): static
    {
        $clone = clone $this;

        $clone->oneOf = Dialect::oneOf(...$builder);

        return $clone;
    }

    final public function additionalProperties(DescriptorContract|bool $schema): static
    {
        $clone = clone $this;

        $clone->additionalProperties = Dialect::additionalProperties($schema);

        return $clone;
    }

    final public function properties(Property ...$property): static
    {
        $clone = clone $this;

        $clone->properties = Dialect::properties(...$property);

        return $clone;
    }

    final public function dependentRequired(Dependency ...$dependency): static
    {
        $clone = clone $this;

        $clone->dependentRequired = Dialect::dependentRequired(...$dependency);

        return $clone;
    }

    final public function maxProperties(int $value): static
    {
        $clone = clone $this;

        $clone->maxProperties = Dialect::maxProperties($value);

        return $clone;
    }

    final public function minProperties(int $value): static
    {
        $clone = clone $this;

        $clone->minProperties = Dialect::minProperties($value);

        return $clone;
    }

    final public function required(string ...$property): static
    {
        $clone = clone $this;

        $clone->required = Dialect::required(...$property);

        return $clone;
    }

    final public function default(mixed $value): static
    {
        $clone = clone $this;

        $clone->defaultValue = Dialect::default($value);

        return $clone;
    }

    final public function deprecated(bool $value): static
    {
        $clone = clone $this;

        $clone->deprecated = Dialect::deprecated($value);

        return $clone;
    }

    final public function description(string $value): static
    {
        $clone = clone $this;

        $clone->description = Dialect::description($value);

        return $clone;
    }

    final public function examples(mixed ...$example): static
    {
        $clone = clone $this;

        $clone->examples = Dialect::examples(...$example);

        return $clone;
    }

    final public function readOnly(bool $value): static
    {
        $clone = clone $this;

        $clone->isReadOnly = Dialect::readOnly($value);

        return $clone;
    }

    final public function writeOnly(bool $value): static
    {
        $clone = clone $this;

        $clone->isWriteOnly = Dialect::writeOnly($value);

        return $clone;
    }

    final public function title(string $value): static
    {
        $clone = clone $this;

        $clone->title = Dialect::title($value);

        return $clone;
    }

    final public function const(mixed $value): static
    {
        $clone = clone $this;

        $clone->constant = Dialect::const($value);

        return $clone;
    }

    final public function enum(...$value): static
    {
        $clone = clone $this;

        $clone->enum = Dialect::enum(...$value);

        return $clone;
    }

    final public function jsonSerialize(): array
    {
        $keywords = [];
        if ($this->schema instanceof Schema) {
            $keywords[$this->schema::name()] = $this->schema->value();
        }
        if ($this->id instanceof Id) {
            $keywords[$this->id::name()] = $this->id->value();
        }
        if ($this->vocabulary instanceof Vocabulary) {
            $keywords[$this->vocabulary::name()] = $this->vocabulary->value();
        }
        if ($this->anchor instanceof Anchor) {
            $keywords[$this->anchor::name()] = $this->anchor->value();
        }
        if ($this->ref instanceof Ref) {
            $keywords[$this->ref::name()] = $this->ref->value();
        }
        if ($this->dynamicAnchor instanceof DynamicAnchor) {
            $keywords[$this->dynamicAnchor::name()] = $this->dynamicAnchor->value();
        }
        if ($this->dynamicRef instanceof DynamicRef) {
            $keywords[$this->dynamicRef::name()] = $this->dynamicRef->value();
        }
        if ($this->comment instanceof Comment) {
            $keywords[$this->comment::name()] = $this->comment->value();
        }
        if ($this->title instanceof Title) {
            $keywords[$this->title::name()] = $this->title->value();
        }
        if ($this->description instanceof Description) {
            $keywords[$this->description::name()] = $this->description->value();
        }
        if ($this->allOf instanceof AllOf) {
            $keywords[$this->allOf::name()] = $this->allOf->value();
        }
        if ($this->anyOf instanceof AnyOf) {
            $keywords[$this->anyOf::name()] = $this->anyOf->value();
        }
        if ($this->oneOf instanceof OneOf) {
            $keywords[$this->oneOf::name()] = $this->oneOf->value();
        }
        if ($this->type instanceof Type) {
            $keywords[$this->type::name()] = $this->type->value();
        }
        if ($this->constant instanceof Constant) {
            $keywords[$this->constant::name()] = $this->constant->value();
        }
        if ($this->enum instanceof Enum) {
            $keywords[$this->enum::name()] = $this->enum->value();
        }
        if ($this->items instanceof Items) {
            $keywords[$this->items::name()] = $this->items->value();
        }
        if ($this->additionalProperties instanceof AdditionalProperties) {
            $keywords[$this->additionalProperties::name()] = $this->additionalProperties->value();
        }
        if ($this->properties instanceof Properties) {
            $keywords[$this->properties::name()] = $this->properties->value();
        }
        if ($this->unevaluatedItems instanceof UnevaluatedItems) {
            $keywords[$this->unevaluatedItems::name()] = $this->unevaluatedItems->value();
        }
        if ($this->unevaluatedProperties instanceof UnevaluatedProperties) {
            $keywords[$this->unevaluatedProperties::name()] = $this->unevaluatedProperties->value();
        }
        if ($this->format instanceof Format) {
            $keywords[$this->format::name()] = $this->format->value();
        }
        if ($this->maxLength instanceof MaxLength) {
            $keywords[$this->maxLength::name()] = $this->maxLength->value();
        }
        if ($this->minLength instanceof MinLength) {
            $keywords[$this->minLength::name()] = $this->minLength->value();
        }
        if ($this->pattern instanceof Pattern) {
            $keywords[$this->pattern::name()] = $this->pattern->value();
        }
        if ($this->exclusiveMaximum instanceof ExclusiveMaximum) {
            $keywords[$this->exclusiveMaximum::name()] = $this->exclusiveMaximum->value();
        }
        if ($this->exclusiveMinimum instanceof ExclusiveMinimum) {
            $keywords[$this->exclusiveMinimum::name()] = $this->exclusiveMinimum->value();
        }
        if ($this->maximum instanceof Maximum) {
            $keywords[$this->maximum::name()] = $this->maximum->value();
        }
        if ($this->minimum instanceof Minimum) {
            $keywords[$this->minimum::name()] = $this->minimum->value();
        }
        if ($this->multipleOf instanceof MultipleOf) {
            $keywords[$this->multipleOf::name()] = $this->multipleOf->value();
        }
        if ($this->maxContains instanceof MaxContains) {
            $keywords[$this->maxContains::name()] = $this->maxContains->value();
        }
        if ($this->minContains instanceof MinContains) {
            $keywords[$this->minContains::name()] = $this->minContains->value();
        }
        if ($this->maxItems instanceof MaxItems) {
            $keywords[$this->maxItems::name()] = $this->maxItems->value();
        }
        if ($this->minItems instanceof MinItems) {
            $keywords[$this->minItems::name()] = $this->minItems->value();
        }
        if ($this->uniqueItems instanceof UniqueItems) {
            $keywords[$this->uniqueItems::name()] = $this->uniqueItems->value();
        }
        if ($this->dependentRequired instanceof DependentRequired) {
            $keywords[$this->dependentRequired::name()] = $this->dependentRequired->value();
        }
        if ($this->maxProperties instanceof MaxProperties) {
            $keywords[$this->maxProperties::name()] = $this->maxProperties->value();
        }
        if ($this->minProperties instanceof MinProperties) {
            $keywords[$this->minProperties::name()] = $this->minProperties->value();
        }
        if ($this->required instanceof Required) {
            $keywords[$this->required::name()] = $this->required->value();
        }
        if ($this->examples instanceof Examples) {
            $keywords[$this->examples::name()] = $this->examples->value();
        }
        if ($this->deprecated instanceof Deprecated) {
            $keywords[$this->deprecated::name()] = $this->deprecated->value();
        }
        if ($this->isReadOnly instanceof IsReadOnly) {
            $keywords[$this->isReadOnly::name()] = $this->isReadOnly->value();
        }
        if ($this->isWriteOnly instanceof IsWriteOnly) {
            $keywords[$this->isWriteOnly::name()] = $this->isWriteOnly->value();
        }
        if ($this->defaultValue instanceof DefaultValue) {
            $keywords[$this->defaultValue::name()] = $this->defaultValue->value();
        }
        if ($this->defs instanceof Defs) {
            $keywords[$this->defs::name()] = $this->defs->value();
        }

        return $keywords;
    }
}
