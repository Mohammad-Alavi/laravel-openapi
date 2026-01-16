<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * Server URL.
 *
 * A URL to the target host. This URL supports Server Variables and MAY be
 * relative, to indicate that the host location is relative to the location
 * where the OpenAPI document is being served. Variable substitutions will
 * be made when a variable is named in {brackets}. This is a REQUIRED field.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#server-object
 */
final readonly class URL extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::regex(
            $this->value,
            '~^(?:(?:https?|ftp|file)://(?:[\w-]+|\{\w+})(?:\.(?:[\w-]+|\{\w+}))*(?::(?:\d+|\{\w+}))?(?:/[^\s?#]*)?(?:\?[^\s#]*)?(?:#\S*)?|/[^\s?#]*)$~',
        );
    }

    public static function create(string $url): self
    {
        return new self($url);
    }

    public function value(): string
    {
        return $this->value;
    }
}
