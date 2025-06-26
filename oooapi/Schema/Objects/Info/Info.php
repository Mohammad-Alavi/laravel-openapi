<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\TermsOfService;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Info extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;
    private TermsOfService|null $termsOfService = null;
    private Contact|null $contact = null;
    private License|null $license = null;

    private function __construct(
        private Title $title,
        private Version $version,
    ) {
    }

    public static function create(
        Title $title,
        Version $version,
    ): self {
        return new self($title, $version);
    }

    /**
     * The title of the API.
     */
    public function title(Title $title): self
    {
        $clone = clone $this;

        $clone->title = $title;

        return $clone;
    }

    /**
     * A short summary of the API.
     */
    public function summary(Summary|null $summary): self
    {
        $clone = clone $this;

        $clone->summary = $summary;

        return $clone;
    }

    public function description(Description|null $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function termsOfService(TermsOfService|null $termsOfService): self
    {
        $clone = clone $this;

        $clone->termsOfService = $termsOfService;

        return $clone;
    }

    public function contact(Contact|null $contact): self
    {
        $clone = clone $this;

        $clone->contact = $contact;

        return $clone;
    }

    public function license(License|null $license): self
    {
        $clone = clone $this;

        $clone->license = $license;

        return $clone;
    }

    public function version(Version $version): self
    {
        $clone = clone $this;

        $clone->version = $version;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $this->description,
            'termsOfService' => $this->termsOfService,
            'contact' => $this->contact,
            'license' => $this->license,
            'version' => $this->version,
        ]);
    }
}
