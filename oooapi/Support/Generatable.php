<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support;

// TODO: is it possible to make this class readonly?
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;

abstract class Generatable implements OASObject, \JsonSerializable
{
    use Generator;
}
