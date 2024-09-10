<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $name
 * @property string|null $namespace
 * @property string|null $prefix
 * @property bool|null $attribute
 * @property bool|null $wrapped
 */
class Xml extends BaseObject
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $namespace;

    /**
     * @var string|null
     */
    protected $prefix;

    /**
     * @var bool|null
     */
    protected $attribute;

    /**
     * @var bool|null
     */
    protected $wrapped;

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
    public function namespace(string|null $namespace): self
    {
        $instance = clone $this;

        $instance->namespace = $namespace;

        return $instance;
    }

    /**
     * @return static
     */
    public function prefix(string|null $prefix): self
    {
        $instance = clone $this;

        $instance->prefix = $prefix;

        return $instance;
    }

    /**
     * @return static
     */
    public function attribute(bool|null $attribute = true): self
    {
        $instance = clone $this;

        $instance->attribute = $attribute;

        return $instance;
    }

    /**
     * @return static
     */
    public function wrapped(bool|null $wrapped = true): self
    {
        $instance = clone $this;

        $instance->wrapped = $wrapped;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'namespace' => $this->namespace,
            'prefix' => $this->prefix,
            'attribute' => $this->attribute,
            'wrapped' => $this->wrapped,
        ]);
    }
}
