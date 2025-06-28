<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

// TODO: is it possible to make this class readonly?
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generator;

abstract readonly class ReadonlyGenerator implements OASObject, \JsonSerializable
{
    use Generator;
}
