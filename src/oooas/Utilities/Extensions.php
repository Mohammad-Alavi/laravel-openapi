<?php

namespace MohammadAlavi\ObjectOrientedOAS\Utilities;

use ArrayAccess;
use MohammadAlavi\ObjectOrientedOAS\Exceptions\ExtensionDoesNotExistException;

/**
 * @internal
 */
class Extensions implements \ArrayAccess
{
    public const X_EMPTY_VALUE = 'X_EMPTY_VALUE';

    /**
     * @var array
     */
    protected $items = [];

    /**
     * Whether a offset exists.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->items[$this->normalizeOffset($offset)]);
    }

    /**
     * Offset to retrieve.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @return mixed can return all value types
     *
     * @throws ExtensionDoesNotExistException
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new ExtensionDoesNotExistException("[{$offset}] is not a valid extension.");
        }

        return $this->items[$this->normalizeOffset($offset)];
    }

    /**
     * Offset to set.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetset.php
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if ($value === static::X_EMPTY_VALUE) {
            $this->offsetUnset($offset);

            return;
        }

        $this->items[$this->normalizeOffset($offset)] = $value;
    }

    /**
     * Offset to unset.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetunset.php
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            return;
        }

        unset($this->items[$this->normalizeOffset($offset)]);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return string
     */
    protected function normalizeOffset($offset)
    {
        if (0 === mb_strpos($offset, 'x-')) {
            return $offset;
        }

        return 'x-' . $offset;
    }
}
