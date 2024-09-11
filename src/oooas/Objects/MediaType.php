<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $mediaType
 * @property Schema|null $schema
 * @property Example|null $example
 * @property Example[]|null $examples
 * @property Encoding[]|null $encoding
 */
class MediaType extends BaseObject
{
    public const MEDIA_TYPE_APPLICATION_JSON = 'application/json';
    public const MEDIA_TYPE_APPLICATION_PDF = 'application/pdf';
    public const MEDIA_TYPE_IMAGE_JPEG = 'image/jpeg';
    public const MEDIA_TYPE_IMAGE_PNG = 'image/png';
    public const MEDIA_TYPE_TEXT_CALENDAR = 'text/calendar';
    public const MEDIA_TYPE_TEXT_PLAIN = 'text/plain';
    public const MEDIA_TYPE_TEXT_XML = 'text/xml';
    public const MEDIA_TYPE_APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    protected string|null $mediaType = null;
    protected Schema|null $schema = null;
    protected Example|null $example = null;

    /**
     * @var Example[]|null
     */
    protected array|null $examples = null;

    /**
     * @var Encoding[]|null
     */
    protected array|null $encoding = null;

    /**
     * @return static
     */
    public static function json(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_APPLICATION_JSON);
    }

    /**
     * @return static
     */
    public function mediaType(string|null $mediaType): self
    {
        $instance = clone $this;

        $instance->mediaType = $mediaType;

        return $instance;
    }

    /**
     * @return static
     */
    public static function pdf(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_APPLICATION_PDF);
    }

    /**
     * @return static
     */
    public static function jpeg(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_IMAGE_JPEG);
    }

    /**
     * @return static
     */
    public static function png(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_IMAGE_PNG);
    }

    /**
     * @return static
     */
    public static function calendar(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_TEXT_CALENDAR);
    }

    /**
     * @return static
     */
    public static function plainText(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_TEXT_PLAIN);
    }

    /**
     * @return static
     */
    public static function xml(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_TEXT_XML);
    }

    /**
     * @return static
     */
    public static function formUrlEncoded(string|null $objectId = null): self
    {
        return static::create($objectId)
            ->mediaType(static::MEDIA_TYPE_APPLICATION_X_WWW_FORM_URLENCODED);
    }

    /**
     * @return static
     */
    public function schema(SchemaContract|null $schema): self
    {
        $instance = clone $this;

        $instance->schema = $schema;

        return $instance;
    }

    /**
     * @return static
     */
    public function example(Example|null $example): self
    {
        $instance = clone $this;

        $instance->example = $example;

        return $instance;
    }

    /**
     * @param Example[]|null $example
     *
     * @return static
     */
    public function examples(Example ...$example): self
    {
        $instance = clone $this;

        $instance->examples = [] !== $example ? $example : null;

        return $instance;
    }

    /**
     * @param Encoding[] $encoding
     *
     * @return static
     */
    public function encoding(Encoding ...$encoding): self
    {
        $instance = clone $this;

        $instance->encoding = [] !== $encoding ? $encoding : null;

        return $instance;
    }

    protected function generate(): array
    {
        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->objectId] = $example->toArray();
        }

        $encodings = [];
        foreach ($this->encoding ?? [] as $encoding) {
            $encodings[$encoding->objectId] = $encoding->toArray();
        }

        return Arr::filter([
            'schema' => $this->schema,
            'example' => $this->example,
            'examples' => [] !== $examples ? $examples : null,
            'encoding' => [] !== $encodings ? $encodings : null,
        ]);
    }
}
