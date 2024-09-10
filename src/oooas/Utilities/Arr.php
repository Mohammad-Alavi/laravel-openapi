<?php

namespace MohammadAlavi\ObjectOrientedOAS\Utilities;

use MohammadAlavi\ObjectOrientedOAS\Objects\BaseObject;

/**
 * @internal
 */
class Arr
{
    public static function filter(array $array): array
    {
        foreach ($array as $index => &$value) {
            // If the value is an object, then parse to array.
            if ($value instanceof BaseObject) {
                $value = $value->toArray();
            }

            // If the value is a filled array then recursively filter it.
            if (is_array($value)) {
                $value = static::filter($value);
                continue;
            }

            // If the value is a specification extension, then skip the null
            // check below.
            if (is_string($index) && 0 === mb_strpos($index, 'x-')) {
                continue;
            }

            // If the value is null then remove it.
            if (null === $value) {
                unset($array[$index]);
                continue;
            }
        }

        return $array;
    }
}
