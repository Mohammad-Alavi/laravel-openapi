<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * XML Namespace URI.
 *
 * The URI of the namespace definition. This MUST be in the form of an absolute URI.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#xml-object
 */
final readonly class XmlNamespace extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value, 'XML namespace cannot be empty.');

        // XML namespaces must be valid URIs
        $parsed = parse_url($value);
        Assert::notFalse(
            $parsed,
            sprintf('XML namespace "%s" is not a valid URI.', $value),
        );

        // Should have a scheme for absolute URI
        Assert::keyExists(
            $parsed,
            'scheme',
            sprintf('XML namespace "%s" must be an absolute URI with a scheme.', $value),
        );
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
