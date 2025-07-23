<?php

namespace MohammadAlavi\Laragen;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Support\RuleExtractor;
use MohammadAlavi\Laragen\Support\RuleToSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final readonly class Laragen
{
    public static function getBodyParameters(Route $route): ObjectRestrictor
    {
        $rules = app(RuleExtractor::class)->extractFrom($route);
        $schema = app(RuleToSchema::class)->transform(
            $rules,
        )->compile();

        if (is_array($schema)) {
            return Schema::from($schema);
        }

        return Schema::from([]);
    }
}
