<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Identifier;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;
use Webmozart\Assert\Assert;

final class License extends ExtensibleObject
{
    private Identifier|null $identifier = null;
    private URL|null $url = null;

    private function __construct(
        private readonly Name $name,
    ) {
    }

    public function identifier(string $identifier): self
    {
        Assert::null(
            $this->url,
            'identifier and url fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->identifier = Identifier::create($identifier);

        return $clone;
    }

    public static function create(string $name): self
    {
        return new self(Name::create($name));
    }

    public function url(string $url): self
    {
        Assert::null(
            $this->identifier,
            'url and identifier fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->url = URL::create($url);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'identifier' => $this->identifier,
            'url' => $this->url,
        ]);
    }
}
