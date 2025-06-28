<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

final readonly class OpenAPI extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value, 'OpenAPI version cannot be empty.');
        Assert::regex(
            $value,
            '/^\d+\.\d+\.\d+$/',
            'OpenAPI version must be in the format X.Y.Z where X, Y, and Z are integers.',
        );
    }

    public static function v310(): self
    {
        return new self('3.1.0');
    }

    public static function v311(): self
    {
        return new self('3.1.1');
    }

    public function value(): string
    {
        return $this->value;
    }
}
