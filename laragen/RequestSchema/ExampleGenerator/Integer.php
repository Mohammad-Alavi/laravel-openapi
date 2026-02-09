<?php

namespace MohammadAlavi\Laragen\RequestSchema\ExampleGenerator;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use Workbench\App\Laragen\Http\Requests\AutogenRequestBodyRequest;

final readonly class Integer extends Example
{
    public static function rule(): string
    {
        return 'integer';
    }

    public function values(): array
    {
        return [1, 42, 100, -1, -42, -100];
    }

    public function format(): DefinedFormat
    {
        return IntegerFormat::INT32;
    }

    public function except(): array
    {
        return [
            AutogenRequestBodyRequest::class => 'height',
        ];
    }

    public function only(): array
    {
        return [
            AutogenRequestBodyRequest::class => ['age'],
        ];
    }
}
