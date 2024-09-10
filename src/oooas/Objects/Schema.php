<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $title
 * @property string|null $description
 * @property mixed[]|null $enum
 * @property mixed|null $default
 * @property string|null $format
 * @property string|null $type
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Schema[]|null $items
 * @property int|null $maxItems
 * @property int|null $minItems
 * @property bool|null $uniqueItems
 * @property string|null $pattern
 * @property int|null $maxLength
 * @property int|null $minLength
 * @property int|float|null $maximum
 * @property int|float|null $exclusiveMaximum
 * @property int|float|null $minimum
 * @property int|float|null $exclusiveMinimum
 * @property int|float|null $multipleOf
 * @property string[]|null $required
 * @property \MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract[]|null $properties
 * @property Schema|null $additionalProperties
 * @property int|null $maxProperties
 * @property int|null $minProperties
 * @property bool|null $nullable
 * @property Discriminator|null $discriminator
 * @property bool|null $readOnly
 * @property bool|null $writeOnly
 * @property Xml|null $xml
 * @property ExternalDocs|null $externalDocs
 * @property mixed|null $example
 * @property bool|null $deprecated
 */
class Schema extends BaseObject implements SchemaContract
{
    public const TYPE_ARRAY = 'array';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_NUMBER = 'number';
    public const TYPE_OBJECT = 'object';
    public const TYPE_STRING = 'string';

    public const FORMAT_INT32 = 'int32';
    public const FORMAT_INT64 = 'int64';
    public const FORMAT_FLOAT = 'float';
    public const FORMAT_DOUBLE = 'double';
    public const FORMAT_BYTE = 'byte';
    public const FORMAT_BINARY = 'binary';
    public const FORMAT_DATE = 'date';
    public const FORMAT_DATE_TIME = 'date-time';
    public const FORMAT_PASSWORD = 'password';
    public const FORMAT_UUID = 'uuid';

    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var mixed[]|null
     */
    protected $enum;

    /**
     * @var mixed|null
     */
    protected $default;

    /**
     * @var string|null
     */
    protected $format;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var Schema|null
     */
    protected $items;

    /**
     * @var int|null
     */
    protected $maxItems;

    /**
     * @var int|null
     */
    protected $minItems;

    /**
     * @var bool|null
     */
    protected $uniqueItems;

    /**
     * @var string|null
     */
    protected $pattern;

    /**
     * @var int|null
     */
    protected $maxLength;

    /**
     * @var int|null
     */
    protected $minLength;

    /**
     * @var int|null
     */
    protected $maximum;

    /**
     * @var int|null
     */
    protected $exclusiveMaximum;

    /**
     * @var int|null
     */
    protected $minimum;

    /**
     * @var int|null
     */
    protected $exclusiveMinimum;

    /**
     * @var int|null
     */
    protected $multipleOf;

    /**
     * @var string[]|null
     */
    protected $required;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract[]|null
     */
    protected $properties;

    /**
     * @var Schema|null
     */
    protected $additionalProperties;

    /**
     * @var int|null
     */
    protected $maxProperties;

    /**
     * @var int|null
     */
    protected $minProperties;

    /**
     * @var bool|null
     */
    protected $nullable;

    /**
     * @var Discriminator|null
     */
    protected $discriminator;

    /**
     * @var bool|null
     */
    protected $readOnly;

    /**
     * @var bool|null
     */
    protected $writeOnly;

    /**
     * @var Xml|null
     */
    protected $xml;

    /**
     * @var ExternalDocs|null
     */
    protected $externalDocs;

    /**
     * @var mixed|null
     */
    protected $example;

    /**
     * @var bool|null
     */
    protected $deprecated;

    /**
     * @return static
     */
    public static function array(string|null $objectId = null): self
    {
        return static::create($objectId)->type(static::TYPE_ARRAY);
    }

    /**
     * @return static
     */
    public static function boolean(string|null $objectId = null): self
    {
        return static::create($objectId)->type(static::TYPE_BOOLEAN);
    }

    /**
     * @return static
     */
    public static function integer(string|null $objectId = null): self
    {
        return static::create($objectId)->type(static::TYPE_INTEGER);
    }

    /**
     * @return static
     */
    public static function number(string|null $objectId = null): self
    {
        return static::create($objectId)->type(static::TYPE_NUMBER);
    }

    /**
     * @return static
     */
    public static function object(string|null $objectId = null): self
    {
        return static::create($objectId)->type(static::TYPE_OBJECT);
    }

    /**
     * @return static
     */
    public static function string(string|null $objectId = null): self
    {
        return static::create($objectId)->type(static::TYPE_STRING);
    }

    /**
     * @return static
     */
    public function title(string|null $title): self
    {
        $instance = clone $this;

        $instance->title = $title;

        return $instance;
    }

    /**
     * @return static
     */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /**
     * @param mixed[] $enum
     *
     * @return static
     */
    public function enum(...$enum): self
    {
        $instance = clone $this;

        $instance->enum = $enum ?: null;

        return $instance;
    }

    /**
     * @param mixed|null $default
     *
     * @return static
     */
    public function default($default): self
    {
        $instance = clone $this;

        $instance->default = $default;

        return $instance;
    }

    /**
     * @return static
     */
    public function format(string|null $format): self
    {
        $instance = clone $this;

        $instance->format = $format;

        return $instance;
    }

    /**
     * @return static
     */
    public function type(string|null $type): self
    {
        $instance = clone $this;

        $instance->type = $type;

        return $instance;
    }

    /**
     * @return static
     */
    public function items(SchemaContract $items): self
    {
        $instance = clone $this;

        $instance->items = $items;

        return $instance;
    }

    /**
     * @return static
     */
    public function maxItems(int|null $maxItems): self
    {
        $instance = clone $this;

        $instance->maxItems = $maxItems;

        return $instance;
    }

    /**
     * @return static
     */
    public function minItems(int|null $minItems): self
    {
        $instance = clone $this;

        $instance->minItems = $minItems;

        return $instance;
    }

    /**
     * @return static
     */
    public function uniqueItems(bool|null $uniqueItems = true): self
    {
        $instance = clone $this;

        $instance->uniqueItems = $uniqueItems;

        return $instance;
    }

    /**
     * @return static
     */
    public function pattern(string|null $pattern): self
    {
        $instance = clone $this;

        $instance->pattern = $pattern;

        return $instance;
    }

    /**
     * @return static
     */
    public function maxLength(int|null $maxLength): self
    {
        $instance = clone $this;

        $instance->maxLength = $maxLength;

        return $instance;
    }

    /**
     * @return static
     */
    public function minLength(int|null $minLength): self
    {
        $instance = clone $this;

        $instance->minLength = $minLength;

        return $instance;
    }

    /**
     * @param int|float|null $maximum
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function maximum($maximum): self
    {
        if (
            !is_int($maximum)
            && !is_float($maximum)
            && !is_null($maximum)
        ) {
            throw new InvalidArgumentException('The maximum must either be an int, float or null.');
        }

        $instance = clone $this;

        $instance->maximum = $maximum;

        return $instance;
    }

    /**
     * @param int|float|null $exclusiveMaximum
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function exclusiveMaximum($exclusiveMaximum): self
    {
        if (
            !is_int($exclusiveMaximum)
            && !is_float($exclusiveMaximum)
            && !is_null($exclusiveMaximum)
        ) {
            throw new InvalidArgumentException('The exclusive maximum must either be an int, float or null.');
        }

        $instance = clone $this;

        $instance->exclusiveMaximum = $exclusiveMaximum;

        return $instance;
    }

    /**
     * @param int|float|null $minimum
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function minimum($minimum): self
    {
        if (
            !is_int($minimum)
            && !is_float($minimum)
            && !is_null($minimum)
        ) {
            throw new InvalidArgumentException('The minimum must either be an int, float or null.');
        }

        $instance = clone $this;

        $instance->minimum = $minimum;

        return $instance;
    }

    /**
     * @param int|float|null $exclusiveMinimum
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function exclusiveMinimum($exclusiveMinimum): self
    {
        if (
            !is_int($exclusiveMinimum)
            && !is_float($exclusiveMinimum)
            && !is_null($exclusiveMinimum)
        ) {
            throw new InvalidArgumentException('The exclusive minimum must either be an int, float, or null.');
        }

        $instance = clone $this;

        $instance->exclusiveMinimum = $exclusiveMinimum;

        return $instance;
    }

    /**
     * @param int|float|null $multipleOf
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function multipleOf($multipleOf): self
    {
        if (
            !is_int($multipleOf)
            && !is_float($multipleOf)
            && !is_null($multipleOf)
        ) {
            throw new InvalidArgumentException('The multiple of must either be an int, float or null.');
        }

        $instance = clone $this;

        $instance->multipleOf = $multipleOf;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Schema[]|string[] $required
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function required(...$required): self
    {
        // Only allow Schema instances and strings.
        foreach ($required as &$require) {
            // If a Schema instance was passed in then extract it's name string.
            if ($require instanceof Schema) {
                $require = $require->objectId;
                continue;
            }

            if (is_string($require)) {
                continue;
            }

            throw new InvalidArgumentException(sprintf('The required must either be an instance of [%s] or a string.', Schema::class));
        }

        $instance = clone $this;

        $instance->required = $required ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract[] $properties
     *
     * @return static
     */
    public function properties(SchemaContract ...$properties): self
    {
        $instance = clone $this;

        $instance->properties = $properties ?: null;

        return $instance;
    }

    /**
     * @return static
     */
    public function additionalProperties(Schema|null $additionalProperties): self
    {
        $instance = clone $this;

        $instance->additionalProperties = $additionalProperties;

        return $instance;
    }

    /**
     * @return static
     */
    public function maxProperties(int|null $maxProperties): self
    {
        $instance = clone $this;

        $instance->maxProperties = $maxProperties;

        return $instance;
    }

    /**
     * @return static
     */
    public function minProperties(int|null $minProperties): self
    {
        $instance = clone $this;

        $instance->minProperties = $minProperties;

        return $instance;
    }

    /**
     * @return static
     */
    public function nullable(bool|null $nullable = true): self
    {
        $instance = clone $this;

        $instance->nullable = $nullable;

        return $instance;
    }

    /**
     * @return static
     */
    public function discriminator(Discriminator|null $discriminator): self
    {
        $instance = clone $this;

        $instance->discriminator = $discriminator;

        return $instance;
    }

    /**
     * @return static
     */
    public function readOnly(bool|null $readOnly = true): self
    {
        $instance = clone $this;

        $instance->readOnly = $readOnly;

        return $instance;
    }

    /**
     * @return static
     */
    public function writeOnly(bool|null $writeOnly = true): self
    {
        $instance = clone $this;

        $instance->writeOnly = $writeOnly;

        return $instance;
    }

    /**
     * @return static
     */
    public function xml(Xml|null $xml): self
    {
        $instance = clone $this;

        $instance->xml = $xml;

        return $instance;
    }

    /**
     * @return static
     */
    public function externalDocs(ExternalDocs|null $externalDocs): self
    {
        $instance = clone $this;

        $instance->externalDocs = $externalDocs;

        return $instance;
    }

    /**
     * @param mixed|null $example
     *
     * @return static
     */
    public function example($example): self
    {
        $instance = clone $this;

        $instance->example = $example;

        return $instance;
    }

    /**
     * @return static
     */
    public function deprecated(bool|null $deprecated = true): self
    {
        $instance = clone $this;

        $instance->deprecated = $deprecated;

        return $instance;
    }

    protected function generate(): array
    {
        $properties = [];
        foreach ($this->properties ?? [] as $property) {
            $properties[$property->objectId] = $property->toArray();
        }

        return Arr::filter([
            'title' => $this->title,
            'description' => $this->description,
            'enum' => $this->enum,
            'default' => $this->default,
            'format' => $this->format,
            'type' => $this->type,
            'items' => $this->items,
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
            'properties' => $properties ?: null,
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
