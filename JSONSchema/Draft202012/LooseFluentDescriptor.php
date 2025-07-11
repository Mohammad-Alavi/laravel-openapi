<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\FluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchemaFactory;
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

class LooseFluentDescriptor implements FluentDescriptor
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

    final private function __construct(
        private Schema|null $schema = null,
    ) {
    }

    /**
     * Create a new instance of the Descriptor without any schema.
     */
    public static function withoutSchema(): static
    {
        return new static(null);
    }

    /**
     * Create a new instance of the Descriptor with a schema.
     */
    public static function create(string $schema = 'https://json-schema.org/draft-2020-12/schema'): static
    {
        return new static(Dialect::schema($schema));
    }

    public function schema(string $uri): static
    {
        $clone = clone $this;

        $clone->schema = Dialect::schema($uri);

        return $clone;
    }

    public function anchor(string $value): static
    {
        $clone = clone $this;

        $clone->anchor = Dialect::anchor($value);

        return $clone;
    }

    public function comment(string $value): static
    {
        $clone = clone $this;

        $clone->comment = Dialect::comment($value);

        return $clone;
    }

    public function defs(Def ...$def): static
    {
        $clone = clone $this;

        $clone->defs = Dialect::defs(...$def);

        return $clone;
    }

    public function dynamicAnchor(string $value): static
    {
        $clone = clone $this;

        $clone->dynamicAnchor = Dialect::dynamicAnchor($value);

        return $clone;
    }

    public function dynamicRef(string $uri): static
    {
        $clone = clone $this;

        $clone->dynamicRef = Dialect::dynamicRef($uri);

        return $clone;
    }

    public function exclusiveMaximum(float $value): static
    {
        $clone = clone $this;

        $clone->exclusiveMaximum = Dialect::exclusiveMaximum($value);

        return $clone;
    }

    public function exclusiveMinimum(float $value): static
    {
        $clone = clone $this;

        $clone->exclusiveMinimum = Dialect::exclusiveMinimum($value);

        return $clone;
    }

    public function format(DefinedFormat $definedFormat): static
    {
        $clone = clone $this;

        $clone->format = Dialect::format($definedFormat);

        return $clone;
    }

    public function id(string $uri): static
    {
        $clone = clone $this;

        $clone->id = Dialect::id($uri);

        return $clone;
    }

    public function maximum(float $value): static
    {
        $clone = clone $this;

        $clone->maximum = Dialect::maximum($value);

        return $clone;
    }

    public function maxLength(int $value): static
    {
        $clone = clone $this;

        $clone->maxLength = Dialect::maxLength($value);

        return $clone;
    }

    public function minimum(float $value): static
    {
        $clone = clone $this;

        $clone->minimum = Dialect::minimum($value);

        return $clone;
    }

    public function minLength(int $value): static
    {
        $clone = clone $this;

        $clone->minLength = Dialect::minLength($value);

        return $clone;
    }

    public function multipleOf(float $value): static
    {
        $clone = clone $this;

        $clone->multipleOf = Dialect::multipleOf($value);

        return $clone;
    }

    public function pattern(string $value): static
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
    public function ref(string $uri): static
    {
        $clone = clone $this;

        $clone->ref = Dialect::ref($uri);

        return $clone;
    }

    public function type(Type|string ...$type): static
    {
        $clone = clone $this;

        $clone->type = Dialect::type(...$type);

        return $clone;
    }

    public function vocabulary(Vocab ...$vocab): static
    {
        $clone = clone $this;

        $clone->vocabulary = Dialect::vocabulary(...$vocab);

        return $clone;
    }

    public function unevaluatedItems(JSONSchema $Descriptor): static
    {
        $clone = clone $this;

        $clone->unevaluatedItems = Dialect::unevaluatedItems($Descriptor);

        return $clone;
    }

    public function unevaluatedProperties(JSONSchema $Descriptor): static
    {
        $clone = clone $this;

        $clone->unevaluatedProperties = Dialect::unevaluatedProperties($Descriptor);

        return $clone;
    }

    public function maxContains(int $value): static
    {
        $clone = clone $this;

        $clone->maxContains = Dialect::maxContains($value);

        return $clone;
    }

    public function minContains(int $value): static
    {
        $clone = clone $this;

        $clone->minContains = Dialect::minContains($value);

        return $clone;
    }

    public function maxItems(int $value): static
    {
        $clone = clone $this;

        $clone->maxItems = Dialect::maxItems($value);

        return $clone;
    }

    public function minItems(int $value): static
    {
        $clone = clone $this;

        $clone->minItems = Dialect::minItems($value);

        return $clone;
    }

    public function uniqueItems(bool $value = true): static
    {
        $clone = clone $this;

        $clone->uniqueItems = Dialect::uniqueItems($value);

        return $clone;
    }

    public function items(JSONSchema|JSONSchemaFactory $schema): static
    {
        $clone = clone $this;

        if ($schema instanceof JSONSchemaFactory) {
            $schema = $schema->build();
        }

        $clone->items = Dialect::items($schema);

        return $clone;
    }

    public function allOf(JSONSchema|JSONSchemaFactory ...$schema): static
    {
        $clone = clone $this;

        $schemas = array_map(
            static function (JSONSchema|JSONSchemaFactory $schema): JSONSchema {
                if ($schema instanceof JSONSchemaFactory) {
                    return $schema->build();
                }

                return $schema;
            },
            $schema,
        );

        $clone->allOf = Dialect::allOf(...$schemas);

        return $clone;
    }

    public function anyOf(JSONSchema|JSONSchemaFactory ...$schema): static
    {
        $clone = clone $this;

        $schemas = array_map(
            static function (JSONSchema|JSONSchemaFactory $schema): JSONSchema {
                if ($schema instanceof JSONSchemaFactory) {
                    return $schema->build();
                }

                return $schema;
            },
            $schema,
        );

        $clone->anyOf = Dialect::anyOf(...$schemas);

        return $clone;
    }

    public function oneOf(JSONSchema|JSONSchemaFactory ...$schema): static
    {
        $clone = clone $this;

        $schemas = array_map(
            static function (JSONSchema|JSONSchemaFactory $schema): JSONSchema {
                if ($schema instanceof JSONSchemaFactory) {
                    return $schema->build();
                }

                return $schema;
            },
            $schema,
        );

        $clone->oneOf = Dialect::oneOf(...$schemas);

        return $clone;
    }

    public function additionalProperties(JSONSchema|bool $schema): static
    {
        $clone = clone $this;

        $clone->additionalProperties = Dialect::additionalProperties($schema);

        return $clone;
    }

    public function properties(Property ...$property): static
    {
        $clone = clone $this;

        $clone->properties = Dialect::properties(...$property);

        return $clone;
    }

    public function dependentRequired(Dependency ...$dependency): static
    {
        $clone = clone $this;

        $clone->dependentRequired = Dialect::dependentRequired(...$dependency);

        return $clone;
    }

    public function maxProperties(int $value): static
    {
        $clone = clone $this;

        $clone->maxProperties = Dialect::maxProperties($value);

        return $clone;
    }

    public function minProperties(int $value): static
    {
        $clone = clone $this;

        $clone->minProperties = Dialect::minProperties($value);

        return $clone;
    }

    public function required(string ...$property): static
    {
        $clone = clone $this;

        $clone->required = Dialect::required(...$property);

        return $clone;
    }

    public function default(mixed $value): static
    {
        $clone = clone $this;

        $clone->defaultValue = Dialect::default($value);

        return $clone;
    }

    public function deprecated(): static
    {
        $clone = clone $this;

        $clone->deprecated = Dialect::deprecated();

        return $clone;
    }

    public function description(string $value): static
    {
        $clone = clone $this;

        $clone->description = Dialect::description($value);

        return $clone;
    }

    public function examples(mixed ...$example): static
    {
        $clone = clone $this;

        $clone->examples = Dialect::examples(...$example);

        return $clone;
    }

    public function readOnly(): static
    {
        $clone = clone $this;

        $clone->isReadOnly = Dialect::readOnly();

        return $clone;
    }

    public function writeOnly(): static
    {
        $clone = clone $this;

        $clone->isWriteOnly = Dialect::writeOnly();

        return $clone;
    }

    public function title(string $value): static
    {
        $clone = clone $this;

        $clone->title = Dialect::title($value);

        return $clone;
    }

    public function const(mixed $value): static
    {
        $clone = clone $this;

        $clone->constant = Dialect::const($value);

        return $clone;
    }

    public function enum(...$value): static
    {
        $clone = clone $this;

        $clone->enum = Dialect::enum(...$value);

        return $clone;
    }

    public function jsonSerialize(): array
    {
        $keywords = [];
        if ($this->schema instanceof Schema) {
            $keywords[$this->schema::name()] = $this->schema;
        }
        if ($this->id instanceof Id) {
            $keywords[$this->id::name()] = $this->id;
        }
        if ($this->vocabulary instanceof Vocabulary) {
            $keywords[$this->vocabulary::name()] = $this->vocabulary;
        }
        if ($this->anchor instanceof Anchor) {
            $keywords[$this->anchor::name()] = $this->anchor;
        }
        if ($this->ref instanceof Ref) {
            $keywords[$this->ref::name()] = $this->ref;
        }
        if ($this->dynamicAnchor instanceof DynamicAnchor) {
            $keywords[$this->dynamicAnchor::name()] = $this->dynamicAnchor;
        }
        if ($this->dynamicRef instanceof DynamicRef) {
            $keywords[$this->dynamicRef::name()] = $this->dynamicRef;
        }
        if ($this->comment instanceof Comment) {
            $keywords[$this->comment::name()] = $this->comment;
        }
        if ($this->title instanceof Title) {
            $keywords[$this->title::name()] = $this->title;
        }
        if ($this->description instanceof Description) {
            $keywords[$this->description::name()] = $this->description;
        }
        if ($this->allOf instanceof AllOf) {
            $keywords[$this->allOf::name()] = $this->allOf;
        }
        if ($this->anyOf instanceof AnyOf) {
            $keywords[$this->anyOf::name()] = $this->anyOf;
        }
        if ($this->oneOf instanceof OneOf) {
            $keywords[$this->oneOf::name()] = $this->oneOf;
        }
        if ($this->type instanceof Type) {
            $keywords[$this->type::name()] = $this->type;
        }
        if ($this->constant instanceof Constant) {
            $keywords[$this->constant::name()] = $this->constant;
        }
        if ($this->enum instanceof Enum) {
            $keywords[$this->enum::name()] = $this->enum;
        }
        if ($this->items instanceof Items) {
            $keywords[$this->items::name()] = $this->items;
        }
        if ($this->additionalProperties instanceof AdditionalProperties) {
            $keywords[$this->additionalProperties::name()] = $this->additionalProperties;
        }
        if ($this->properties instanceof Properties) {
            $keywords[$this->properties::name()] = $this->properties;
        }
        if ($this->unevaluatedItems instanceof UnevaluatedItems) {
            $keywords[$this->unevaluatedItems::name()] = $this->unevaluatedItems;
        }
        if ($this->unevaluatedProperties instanceof UnevaluatedProperties) {
            $keywords[$this->unevaluatedProperties::name()] = $this->unevaluatedProperties;
        }
        if ($this->format instanceof Format) {
            $keywords[$this->format::name()] = $this->format;
        }
        if ($this->maxLength instanceof MaxLength) {
            $keywords[$this->maxLength::name()] = $this->maxLength;
        }
        if ($this->minLength instanceof MinLength) {
            $keywords[$this->minLength::name()] = $this->minLength;
        }
        if ($this->pattern instanceof Pattern) {
            $keywords[$this->pattern::name()] = $this->pattern;
        }
        if ($this->exclusiveMaximum instanceof ExclusiveMaximum) {
            $keywords[$this->exclusiveMaximum::name()] = $this->exclusiveMaximum;
        }
        if ($this->exclusiveMinimum instanceof ExclusiveMinimum) {
            $keywords[$this->exclusiveMinimum::name()] = $this->exclusiveMinimum;
        }
        if ($this->maximum instanceof Maximum) {
            $keywords[$this->maximum::name()] = $this->maximum;
        }
        if ($this->minimum instanceof Minimum) {
            $keywords[$this->minimum::name()] = $this->minimum;
        }
        if ($this->multipleOf instanceof MultipleOf) {
            $keywords[$this->multipleOf::name()] = $this->multipleOf;
        }
        if ($this->maxContains instanceof MaxContains) {
            $keywords[$this->maxContains::name()] = $this->maxContains;
        }
        if ($this->minContains instanceof MinContains) {
            $keywords[$this->minContains::name()] = $this->minContains;
        }
        if ($this->maxItems instanceof MaxItems) {
            $keywords[$this->maxItems::name()] = $this->maxItems;
        }
        if ($this->minItems instanceof MinItems) {
            $keywords[$this->minItems::name()] = $this->minItems;
        }
        if ($this->uniqueItems instanceof UniqueItems) {
            $keywords[$this->uniqueItems::name()] = $this->uniqueItems;
        }
        if ($this->dependentRequired instanceof DependentRequired) {
            $keywords[$this->dependentRequired::name()] = $this->dependentRequired;
        }
        if ($this->maxProperties instanceof MaxProperties) {
            $keywords[$this->maxProperties::name()] = $this->maxProperties;
        }
        if ($this->minProperties instanceof MinProperties) {
            $keywords[$this->minProperties::name()] = $this->minProperties;
        }
        if ($this->required instanceof Required) {
            $keywords[$this->required::name()] = $this->required;
        }
        if ($this->examples instanceof Examples) {
            $keywords[$this->examples::name()] = $this->examples;
        }
        if ($this->deprecated instanceof Deprecated) {
            $keywords[$this->deprecated::name()] = $this->deprecated;
        }
        if ($this->isReadOnly instanceof IsReadOnly) {
            $keywords[$this->isReadOnly::name()] = $this->isReadOnly;
        }
        if ($this->isWriteOnly instanceof IsWriteOnly) {
            $keywords[$this->isWriteOnly::name()] = $this->isWriteOnly;
        }
        if ($this->defaultValue instanceof DefaultValue) {
            $keywords[$this->defaultValue::name()] = $this->defaultValue;
        }
        if ($this->defs instanceof Defs) {
            $keywords[$this->defs::name()] = $this->defs;
        }

        return $keywords;
    }
}
