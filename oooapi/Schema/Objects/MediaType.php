<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\HasKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class MediaType extends ExtensibleObject implements HasKey, SimpleCreator
{
    use SimpleCreatorTrait;

    public const MEDIA_TYPE_APPLICATION_JSON = 'application/json';
    public const MEDIA_TYPE_APPLICATION_PDF = 'application/pdf';
    public const MEDIA_TYPE_IMAGE_JPEG = 'image/jpeg';
    public const MEDIA_TYPE_IMAGE_PNG = 'image/png';
    public const MEDIA_TYPE_TEXT_CALENDAR = 'text/calendar';
    public const MEDIA_TYPE_TEXT_PLAIN = 'text/plain';
    public const MEDIA_TYPE_TEXT_XML = 'text/xml';
    public const MEDIA_TYPE_APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    protected string|null $mediaType = null;
    protected JSONSchema|null $schema = null;
    protected Example|null $example = null;

    /** @var Example[]|null */
    protected array|null $examples = null;

    /** @var Encoding[]|null */
    protected array|null $encoding = null;

    // TODO: for MediaType the only valid object keys are mediaType
    // We have to prevent the user from creating an object with an invalid key
    // The best candidate for key is an enum
    // Right now it can be created with any arbitrary string via create method of the parent
    // Also creating a MediaType object with a key is not necessary but we can do it anyway using the create method!
    //  This should be prevented!
    // public static function create(ObjectKeyContract $key = null): static;

    public static function json(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_APPLICATION_JSON);
    }

    public function mediaType(string|null $mediaType): static
    {
        $clone = clone $this;

        $clone->mediaType = $mediaType;

        return $clone;
    }

    public static function pdf(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_APPLICATION_PDF);
    }

    public static function jpeg(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_IMAGE_JPEG);
    }

    public static function png(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_IMAGE_PNG);
    }

    public static function calendar(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_TEXT_CALENDAR);
    }

    public static function plainText(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_TEXT_PLAIN);
    }

    public static function xml(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_TEXT_XML);
    }

    public static function formUrlEncoded(): static
    {
        return static::create()
            ->mediaType(static::MEDIA_TYPE_APPLICATION_X_WWW_FORM_URLENCODED);
    }

    public function schema(JSONSchema|null $schema): static
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    public function example(Example|null $example): static
    {
        $clone = clone $this;

        $clone->example = $example;

        return $clone;
    }

    public function examples(Example ...$example): static
    {
        $clone = clone $this;

        $clone->examples = [] !== $example ? $example : null;

        return $clone;
    }

    public function encoding(Encoding ...$encoding): static
    {
        $clone = clone $this;

        $clone->encoding = [] !== $encoding ? $encoding : null;

        return $clone;
    }

    final public function key(): string
    {
        return $this->mediaType;
    }

    protected function toArray(): array
    {
        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->key()] = $example;
        }

        $encodings = [];
        foreach ($this->encoding ?? [] as $encoding) {
            $encodings[$encoding->key()] = $encoding;
        }

        return Arr::filter([
            'schema' => $this->schema,
            'example' => $this->example,
            'examples' => [] !== $examples ? $examples : null,
            'encoding' => [] !== $encodings ? $encodings : null,
        ]);
    }
}
