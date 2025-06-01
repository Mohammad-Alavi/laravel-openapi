<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Info extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $title = null;
    protected string|null $description = null;
    protected string|null $termsOfService = null;
    protected Contact|null $contact = null;
    protected License|null $license = null;
    protected string|null $version = null;

    public function title(string|null $title): static
    {
        $clone = clone $this;

        $clone->title = $title;

        return $clone;
    }

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function termsOfService(string|null $termsOfService): static
    {
        $clone = clone $this;

        $clone->termsOfService = $termsOfService;

        return $clone;
    }

    public function contact(Contact|null $contact): static
    {
        $clone = clone $this;

        $clone->contact = $contact;

        return $clone;
    }

    public function license(License|null $license): static
    {
        $clone = clone $this;

        $clone->license = $license;

        return $clone;
    }

    public function version(string|null $version): static
    {
        $clone = clone $this;

        $clone->version = $version;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'title' => $this->title,
            'description' => $this->description,
            'termsOfService' => $this->termsOfService,
            'contact' => $this->contact,
            'license' => $this->license,
            'version' => $this->version,
        ]);
    }
}
