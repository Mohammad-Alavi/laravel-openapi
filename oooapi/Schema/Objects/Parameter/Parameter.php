<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\AllowEmptyValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Deprecated;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Required;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\ContentSerialized;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedCookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedHeader;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SerializationRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Parameter extends ExtensibleObject
{
    private Required|null $required = null;
    private Description|null $description = null;
    private Deprecated|null $deprecated = null;
    private AllowEmptyValue|null $allowEmptyValue = null;

    private function __construct(
        private readonly Name $name,
        private readonly In $in,
        private readonly SerializationRule $serializationRule,
    ) {
    }

    public static function cookie(
        Name $name,
        ContentSerialized|SchemaSerializedCookie $serialization,
    ): self {
        return new self($name, In::cookie(), $serialization);
    }

    public static function header(
        Name $name,
        ContentSerialized|SchemaSerializedHeader $serialization,
    ): self {
        return new self($name, In::header(), $serialization);
    }

    public static function path(
        Name $name,
        ContentSerialized|SchemaSerializedPath $serialization,
    ): self {
        return new self($name, In::path(), $serialization);
    }

    public static function query(
        Name $name,
        ContentSerialized|SchemaSerializedQuery $serialization,
    ): self {
        return new self($name, In::query(), $serialization);
    }

    public function description(Description|null $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function required(): self
    {
        $clone = clone $this;

        $clone->required = Required::yes();

        return $clone;
    }

    public function deprecated(): self
    {
        $clone = clone $this;

        $clone->deprecated = Deprecated::yes();

        return $clone;
    }

    public function allowEmptyValue(): self
    {
        $clone = clone $this;

        $clone->allowEmptyValue = AllowEmptyValue::yes();

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'in' => $this->in,
            'description' => $this->description,
            'required' => $this->required,
            'deprecated' => $this->deprecated,
            'allowEmptyValue' => $this->allowEmptyValue,
            ...$this->serializationRule->toArray(),
        ]);
    }
}
