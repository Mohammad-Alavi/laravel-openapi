<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;

/** @internal */
class Arr
{
    // TODO: maybe make a macro for on Laravel Arr facade?
    public static function filter(array $array): array
    {
        foreach ($array as $index => &$value) {
            // If the value is an object, then parse to array.
            if ($value instanceof ExtensibleObject) {
                $value = $value->jsonSerialize();
            }

            // If the value is a filled array, then recursively filter it.
            if (is_array($value)) {
                $value = static::filter($value);
                continue;
            }

            // If the value is a specification extension, then skip the null
            // check below.
            if (is_string($index) && Extension::isExtension($index)) {
                continue;
            }

            // If the value is null, then remove it.
            if (is_null($value)) {
                unset($array[$index]);
            }
        }

        return $array;
    }
}
