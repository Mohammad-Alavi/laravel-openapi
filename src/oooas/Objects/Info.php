<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $title
 * @property string|null $description
 * @property string|null $termsOfService
 * @property Contact|null $contact
 * @property License|null $license
 * @property string|null $version
 */
class Info extends BaseObject
{
    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $termsOfService;

    /**
     * @var Contact|null
     */
    protected $contact;

    /**
     * @var License|null
     */
    protected $license;

    /**
     * @var string|null
     */
    protected $version;

    /**
     * @return static
     */
    public function title(string|null $title): self
    {
        $instance = clone $this;

        $instance->title = $title;

        return $instance;
    }

    /**
     * @return static
     */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /**
     * @return static
     */
    public function termsOfService(string|null $termsOfService): self
    {
        $instance = clone $this;

        $instance->termsOfService = $termsOfService;

        return $instance;
    }

    /**
     * @return static
     */
    public function contact(Contact|null $contact): self
    {
        $instance = clone $this;

        $instance->contact = $contact;

        return $instance;
    }

    /**
     * @return static
     */
    public function license(License|null $license): self
    {
        $instance = clone $this;

        $instance->license = $license;

        return $instance;
    }

    /**
     * @return static
     */
    public function version(string|null $version): self
    {
        $instance = clone $this;

        $instance->version = $version;

        return $instance;
    }

    protected function generate(): array
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
