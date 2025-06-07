<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Trash;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleKeyCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Discriminator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\SchemaComposition;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Xml;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleKeyCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

// TODO: for schema the $key is required I think. It should bre required when creating it via ny construction method
class Schema extends ExtensibleObject implements JsonSchema, SimpleKeyCreator
{
    // TODO: Implement $id from OAS spec 3.1
    // public string|null $id = null;
    use SimpleKeyCreatorTrait;

    public const TYPE_ARRAY = 'array';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_NUMBER = 'number';
    public const TYPE_OBJECT = 'object';

    //    public const TYPE_STRING = 'string';
    public const FORMAT_INT32 = 'int32';
    public const FORMAT_INT64 = 'int64';
    public const FORMAT_FLOAT = 'float';
    public const FORMAT_DOUBLE = 'double';
    public const FORMAT_BYTE = 'byte';
    public const FORMAT_BINARY = 'binary';
    //    public const FORMAT_DATE = 'date';
    //    public const FORMAT_DATE_TIME = 'date-time';
    //    public const FORMAT_PASSWORD = 'password';
    //    public const FORMAT_UUID = 'uuid';

    protected string|null $title = null;
    protected string|null $description = null;

    //    protected array|null $enum = null;
    protected mixed $default = null;
    protected string|null $format = null;

    //    protected string|null $type = null;
    protected JsonSchema|SchemaComposition|ReusableSchemaFactory|null $items = null;
    protected int|null $maxItems = null;
    protected int|null $minItems = null;
    protected bool|null $uniqueItems = null;
    protected string|null $pattern = null;
    protected int|null $maxLength = null;
    protected int|null $minLength = null;
    protected float|int|null $maximum = null;
    protected float|int|null $exclusiveMaximum = null;
    protected float|int|null $minimum = null;
    protected float|int|null $exclusiveMinimum = null;
    protected float|int|null $multipleOf = null;

    /** @var string[]|null */
    protected array|null $required = null;

    /** @var JsonSchema[]|null */
    protected array|null $properties = null;

    protected JsonSchema|null $additionalProperties = null;
    protected int|null $maxProperties = null;
    protected int|null $minProperties = null;
    protected bool|null $nullable = null;
    protected Discriminator|null $discriminator = null;
    protected bool|null $readOnly = null;
    protected bool|null $writeOnly = null;
    protected Xml|null $xml = null;
    protected ExternalDocumentation|null $externalDocs = null;
    protected mixed $example = null;
    protected bool|null $deprecated = null;

    public static function array(string $key): static
    {
        return static::create($key)->type(static::TYPE_ARRAY);
    }

    //    public function type(string|null $type): static
    //    {
    //        $clone = clone $this;
    //
    //        $clone->type = $type;
    //
    //        return $clone;
    //    }

    public static function boolean(string $key): static
    {
        return static::create($key)->type(static::TYPE_BOOLEAN);
    }

    public static function integer(string $key): static
    {
        return static::create($key)->type(static::TYPE_INTEGER);
    }

    public static function number(string $key): static
    {
        return static::create($key)->type(static::TYPE_NUMBER);
    }

    public static function object(string $key): static
    {
        return static::create($key)->type(static::TYPE_OBJECT);
    }

    //    public static function string(string $key): static
    //    {
    //        return static::create($key)->type(static::TYPE_STRING);
    //    }

    public function title(string|null $title): static
    {
        $clone = clone $this;

        $clone->title = $title;

        return $clone;
    }

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function enum(...$enum): static
    {
        $clone = clone $this;

        $clone->enum = [] !== $enum ? $enum : null;

        return $clone;
    }

    public function default(mixed $default): static
    {
        $clone = clone $this;

        $clone->default = $default;

        return $clone;
    }

    public function format(string|null $format): static
    {
        $clone = clone $this;

        $clone->format = $format;

        return $clone;
    }

    public function items(JsonSchema|ReusableSchemaFactory $schemaContract): static
    {
        $clone = clone $this;

        $clone->items = $schemaContract;

        return $clone;
    }

    public function maxItems(int|null $maxItems): static
    {
        $clone = clone $this;

        $clone->maxItems = $maxItems;

        return $clone;
    }

    public function minItems(int|null $minItems): static
    {
        $clone = clone $this;

        $clone->minItems = $minItems;

        return $clone;
    }

    public function uniqueItems(bool|null $uniqueItems = true): static
    {
        $clone = clone $this;

        $clone->uniqueItems = $uniqueItems;

        return $clone;
    }

    public function pattern(string|null $pattern): static
    {
        $clone = clone $this;

        $clone->pattern = $pattern;

        return $clone;
    }

    public function maxLength(int|null $maxLength): static
    {
        $clone = clone $this;

        $clone->maxLength = $maxLength;

        return $clone;
    }

    public function minLength(int|null $minLength): static
    {
        $clone = clone $this;

        $clone->minLength = $minLength;

        return $clone;
    }

    public function maximum(float|int|null $maximum): static
    {
        $clone = clone $this;

        $clone->maximum = $maximum;

        return $clone;
    }

    public function exclusiveMaximum(float|int|null $exclusiveMaximum): static
    {
        $clone = clone $this;

        $clone->exclusiveMaximum = $exclusiveMaximum;

        return $clone;
    }

    public function minimum(float|int|null $minimum): static
    {
        $clone = clone $this;

        $clone->minimum = $minimum;

        return $clone;
    }

    public function exclusiveMinimum(float|int|null $exclusiveMinimum): static
    {
        $clone = clone $this;

        $clone->exclusiveMinimum = $exclusiveMinimum;

        return $clone;
    }

    public function multipleOf(float|int|null $multipleOf): static
    {
        $clone = clone $this;

        $clone->multipleOf = $multipleOf;

        return $clone;
    }

    public function required(self|string ...$required): static
    {
        foreach ($required as &$require) {
            if ($require instanceof self) {
                // TODO: search for objectId keyword usage everywhere
                $require = $require->key();
            }
        }

        $clone = clone $this;

        $clone->required = [] !== $required ? $required : null;

        return $clone;
    }

    public function properties(JsonSchema|ReusableSchemaFactory ...$schemaContract): static
    {
        $clone = clone $this;

        $clone->properties = [] !== $schemaContract ? $schemaContract : null;

        return $clone;
    }

    public function additionalProperties(self|null $additionalProperties): static
    {
        $clone = clone $this;

        $clone->additionalProperties = $additionalProperties;

        return $clone;
    }

    public function maxProperties(int|null $maxProperties): static
    {
        $clone = clone $this;

        $clone->maxProperties = $maxProperties;

        return $clone;
    }

    public function minProperties(int|null $minProperties): static
    {
        $clone = clone $this;

        $clone->minProperties = $minProperties;

        return $clone;
    }

    public function nullable(bool|null $nullable = true): static
    {
        $clone = clone $this;

        $clone->nullable = $nullable;

        return $clone;
    }

    public function discriminator(Discriminator|null $discriminator): static
    {
        $clone = clone $this;

        $clone->discriminator = $discriminator;

        return $clone;
    }

    public function readOnly(bool|null $readOnly = true): static
    {
        $clone = clone $this;

        $clone->readOnly = $readOnly;

        return $clone;
    }

    public function writeOnly(bool|null $writeOnly = true): static
    {
        $clone = clone $this;

        $clone->writeOnly = $writeOnly;

        return $clone;
    }

    public function xml(Xml|null $xml): static
    {
        $clone = clone $this;

        $clone->xml = $xml;

        return $clone;
    }

    public function externalDocs(ExternalDocumentation|null $externalDocs): static
    {
        $clone = clone $this;

        $clone->externalDocs = $externalDocs;

        return $clone;
    }

    public function example(mixed $example): static
    {
        $clone = clone $this;

        $clone->example = $example;

        return $clone;
    }

    public function deprecated(bool|null $deprecated = true): static
    {
        $clone = clone $this;

        $clone->deprecated = $deprecated;

        return $clone;
    }

    protected function toArray(): array
    {
        $properties = [];
        foreach ($this->properties ?? [] as $property) {
            $properties[$property->key()] = $property;
        }

        return Arr::filter([
            'title' => $this->title,
            'description' => $this->description,
            'enum' => $this->enum,
            'default' => $this->default,
            'format' => $this->format,
            'type' => $this->type,
            'items' => $this->items instanceof ReusableSchemaFactory ? ['$ref' => $this->items::ref()] : $this->items,
            'maxItems' => $this->maxItems,
            'minItems' => $this->minItems,
            'uniqueItems' => $this->uniqueItems,
            'pattern' => $this->pattern,
            'maxLength' => $this->maxLength,
            'minLength' => $this->minLength,
            'maximum' => $this->maximum,
            'exclusiveMaximum' => $this->exclusiveMaximum,
            'minimum' => $this->minimum,
            'exclusiveMinimum' => $this->exclusiveMinimum,
            'multipleOf' => $this->multipleOf,
            'required' => $this->required,
            'properties' => [] !== $properties ? $properties : null,
            'additionalProperties' => $this->additionalProperties,
            'maxProperties' => $this->maxProperties,
            'minProperties' => $this->minProperties,
            'nullable' => $this->nullable,
            'discriminator' => $this->discriminator,
            'readOnly' => $this->readOnly,
            'writeOnly' => $this->writeOnly,
            'xml' => $this->xml,
            'externalDocs' => $this->externalDocs,
            'example' => $this->example,
            'deprecated' => $this->deprecated,
        ]);
    }
}
