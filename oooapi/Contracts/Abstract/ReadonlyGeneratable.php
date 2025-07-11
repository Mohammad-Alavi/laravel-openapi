<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

// TODO: is it possible to make this class readonly?
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;

abstract readonly class ReadonlyGeneratable implements OASObject, \JsonSerializable
{
    use Generator;
}
