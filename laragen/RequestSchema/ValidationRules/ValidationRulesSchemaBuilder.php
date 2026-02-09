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
    private const FILE_RULES = ['file', 'image', 'mimes', 'mimetypes'];

    public function build(mixed $detected, Route $route): RequestSchemaResult
    {
        $schema = RuleToSchema::transform($route)->compile();
        $objectSchema = is_array($schema)
            ? Schema::from($schema)
            : Schema::from([]);

        /** @var DetectedValidationRules|null $detected */
        $encoding = null !== $detected && self::hasFileRules($detected)
            ? ContentEncoding::MULTIPART_FORM_DATA
            : ContentEncoding::JSON;

        return new RequestSchemaResult(
            schema: $objectSchema,
            target: self::determineTarget($route),
            encoding: $encoding,
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

    private static function hasFileRules(DetectedValidationRules $detected): bool
    {
        foreach ($detected->rules as $fieldRules) {
            if (!is_array($fieldRules)) {
                continue;
            }

            foreach ($fieldRules as $rule) {
                $ruleName = is_string($rule) ? explode(':', $rule)[0] : null;

                if (null !== $ruleName && in_array($ruleName, self::FILE_RULES, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
