<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\ValidationRules;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\Laragen\Support\RuleToSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final readonly class ValidationRulesSchemaBuilder implements RequestSchemaBuilder
{
    public function build(mixed $detected, Route $route): RequestSchemaResult
    {
        $schema = RuleToSchema::transform($route)->compile();
        $objectSchema = is_array($schema)
            ? Schema::from($schema)
            : Schema::from([]);

        return new RequestSchemaResult(
            schema: $objectSchema,
            target: self::determineTarget($route),
            encoding: ContentEncoding::JSON,
        );
    }

    private static function determineTarget(Route $route): RequestTarget
    {
        /** @var string $httpMethod */
        $httpMethod = $route->methods()[0];
        $method = strtoupper($httpMethod);

        return match ($method) {
            'GET', 'DELETE', 'HEAD' => RequestTarget::QUERY,
            default => RequestTarget::BODY,
        };
    }
}
