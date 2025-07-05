<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\CookieParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\HeaderParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SerializationRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

final class Parameter extends ExtensibleObject
{
    private true|null $required = null;
    private Description|null $description = null;
    private true|null $deprecated = null;
    private true|null $allowEmptyValue = null;

    private function __construct(
        private readonly Name $name,
        private readonly In $in,
        private readonly SerializationRule $serializationRule,
    ) {
    }

    public static function cookie(
        string $name,
        Content|CookieParameter $serialization,
    ): self {
        return new self(Name::create($name), In::cookie(), $serialization);
    }

    public static function header(
        string $name,
        Content|HeaderParameter $serialization,
    ): self {
        return new self(Name::create($name), In::header(), $serialization);
    }

    public static function path(
        string $name,
        Content|PathParameter $serialization,
    ): self {
        return new self(Name::create($name), In::path(), $serialization);
    }

    public static function query(
        string $name,
        Content|QueryParameter $serialization,
    ): self {
        return new self(Name::create($name), In::query(), $serialization);
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function required(): self
    {
        $clone = clone $this;

        $clone->required = true;

        return $clone;
    }

    public function deprecated(): self
    {
        $clone = clone $this;

        $clone->deprecated = true;

        return $clone;
    }

    public function allowEmptyValue(): self
    {
        $clone = clone $this;

        $clone->allowEmptyValue = true;

        return $clone;
    }

    public function getName(): string
    {
        return $this->name->value();
    }

    public function getLocation(): string
    {
        return $this->in->value();
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'in' => $this->in,
            'description' => $this->description,
            'required' => $this->required,
            'deprecated' => $this->deprecated,
            'allowEmptyValue' => $this->allowEmptyValue,
            ...$this->serializationRule->jsonSerialize(), // TODO: Improve? This is different from the usual way of handling serialization rules.
        ]);
    }
}
