<?php

namespace MohammadAlavi\Laragen\Support;

use Illuminate\Foundation\Http\FormRequest;
use LaravelRulesToSchema\Facades\LaravelRulesToSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final readonly class JSONSchemaUtil
{
    /**
     * @param array<string, array<int, string>|string>|class-string<FormRequest> $rules
     *
     * @throws \Exception
     */
    public static function fromRequestRules(array|string $rules): ObjectRestrictor
    {
        return Schema::from(LaravelRulesToSchema::parse($rules)->compile());
    }
}
