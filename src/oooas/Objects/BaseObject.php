<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\PropertyDoesNotExistException;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Extensions;

/**
 * @property string|null $objectId
 * @property string|null $ref
 * @property array|null $x
 */
abstract class BaseObject implements \JsonSerializable
{
    /**
     * @var string|null
     */
    protected $objectId;

    /**
     * @var string|null
     */
    protected $ref;

    /**
     * @var Extensions
     */
    protected $extensions;

    /**
     * BaseObject constructor.
     */
    public function __construct(string|null $objectId = null)
    {
        $this->objectId = $objectId;
        $this->extensions = new Extensions();
    }

    /**
     * @return static
     */
    public static function create(string|null $objectId = null): self
    {
        return new static($objectId);
    }

    /**
     * @return static
     */
    public static function ref(string $ref, string|null $objectId = null): self
    {
        $instance = new static($objectId);

        $instance->ref = $ref;

        return $instance;
    }

    /**
     * @return static
     */
    public function objectId(string|null $objectId): self
    {
        $instance = clone $this;

        $instance->objectId = $objectId;

        return $instance;
    }

    /**
     * @return $this
     */
    public function x(string $key, $value = Extensions::X_EMPTY_VALUE): self
    {
        $instance = clone $this;

        if (0 === mb_strpos($key, 'x-')) {
            $key = mb_substr($key, 2);
        }

        $instance->extensions[$key] = $value;

        return $instance;
    }

    abstract protected function generate(): array;

    public function toArray(): array
    {
        if (null !== $this->ref) {
            return ['$ref' => $this->ref];
        }

        return array_merge(
            $this->generate(),
            $this->extensions->toArray(),
        );
    }

    /**
     * @param int $options
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Specify data which should be serialized to JSON.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @throws PropertyDoesNotExistException
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        // Get all extensions.
        if ('x' === $name) {
            return $this->extensions->toArray();
        }

        // Get a single extension.
        if (0 === mb_strpos($name, 'x-')) {
            $key = mb_strtolower(substr_replace($name, '', 0, 2));

            if (isset($this->extensions[$key])) {
                return $this->extensions[$key];
            }
        }

        throw new PropertyDoesNotExistException("[{$name}] is not a valid property.");
    }
}
