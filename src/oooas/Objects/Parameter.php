<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $name
 * @property string|null $in
 * @property string|null $description
 * @property bool|null $required
 * @property bool|null $deprecated
 * @property bool|null $allowEmptyValue
 * @property string|null $style
 * @property bool|null $explode
 * @property bool|null $allowReserved
 * @property Schema|null $schema
 * @property mixed|null $example
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Example[]|null $examples
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[]|null $content
 */
class Parameter extends BaseObject
{
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

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $in;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var bool|null
     */
    protected $required;

    /**
     * @var bool|null
     */
    protected $deprecated;

    /**
     * @var bool|null
     */
    protected $allowEmptyValue;

    /**
     * @var string|null
     */
    protected $style;

    /**
     * @var bool|null
     */
    protected $explode;

    /**
     * @var string|null
     */
    protected $allowReserved;

    /**
     * @var Schema|null
     */
    protected $schema;

    /**
     * @var mixed|null
     */
    protected $example;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Example[]|null
     */
    protected $examples;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[]|null
     */
    protected $content;

    /**
     * @return static
     */
    public static function query(string|null $objectId = null): self
    {
        return static::create($objectId)->in(static::IN_QUERY);
    }

    /**
     * @return static
     */
    public static function header(string|null $objectId = null): self
    {
        return static::create($objectId)->in(static::IN_HEADER);
    }

    /**
     * @return static
     */
    public static function path(string|null $objectId = null): self
    {
        return static::create($objectId)->in(static::IN_PATH);
    }

    /**
     * @return static
     */
    public static function cookie(string|null $objectId = null): self
    {
        return static::create($objectId)->in(static::IN_COOKIE);
    }

    /**
     * @return static
     */
    public function name(string|null $name): self
    {
        $instance = clone $this;

        $instance->name = $name;

        return $instance;
    }

    /**
     * @return static
     */
    public function in(string|null $in): self
    {
        $instance = clone $this;

        $instance->in = $in;

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
     * @return static
     */
    public function required(bool|null $required = true): self
    {
        $instance = clone $this;

        $instance->required = $required;

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

    /**
     * @return static
     */
    public function allowEmptyValue(bool|null $allowEmptyValue = true): self
    {
        $instance = clone $this;

        $instance->allowEmptyValue = $allowEmptyValue;

        return $instance;
    }

    /**
     * @return static
     */
    public function style(string|null $style): self
    {
        $instance = clone $this;

        $instance->style = $style;

        return $instance;
    }

    /**
     * @return static
     */
    public function explode(bool|null $explode = true): self
    {
        $instance = clone $this;

        $instance->explode = $explode;

        return $instance;
    }

    /**
     * @return static
     */
    public function allowReserved(bool|null $allowReserved = true): self
    {
        $instance = clone $this;

        $instance->allowReserved = $allowReserved;

        return $instance;
    }

    /**
     * @return static
     */
    public function schema(SchemaContract|null $schema): self
    {
        $instance = clone $this;

        $instance->schema = $schema;

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
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Example[]|null $examples
     *
     * @return static
     */
    public function examples(Example ...$examples): self
    {
        $instance = clone $this;

        $instance->examples = $examples ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[] $content
     *
     * @return static
     */
    public function content(MediaType ...$content): self
    {
        $instance = clone $this;

        $instance->content = $content ?: null;

        return $instance;
    }

    protected function generate(): array
    {
        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->objectId] = $example->toArray();
        }

        $content = [];
        foreach ($this->content ?? [] as $contentItem) {
            $content[$contentItem->mediaType] = $contentItem;
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
            'examples' => $examples ?: null,
            'content' => $content ?: null,
        ]);
    }
}
