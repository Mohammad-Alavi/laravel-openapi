<?php

namespace MohammadAlavi\Laragen\Support;

use FluentJsonSchema\FluentSchema;
use Illuminate\Foundation\Http\FormRequest;
use LaravelRulesToSchema\Contracts\RuleParser as LaravelRuleParser;
use LaravelRulesToSchema\LaravelRulesToSchema;
use LaravelRulesToSchema\ValidationRuleNormalizer;
use Mockery\Exception;

final class RuleToSchema extends LaravelRulesToSchema
{
    public static function transform(array|string $rule): FluentSchema
    {
        if (is_string($rule)) {
            if (!class_exists($rule)) {
                throw new \Exception("Class $rule does not implement " . FormRequest::class . ' and can not be parsed.');
            }
            $instance = new $rule();

            $rule = method_exists($instance, 'rules') ? app()->call([$instance, 'rules']) : [];
        }

        $normalizedRules = (new ValidationRuleNormalizer($rule))->getRules();

        $schema = FluentSchema::make()
            ->type()->object()
            ->return();

        foreach ($normalizedRules as $property => $rawRules) {
            $propertySchema = self::parseRulesetOverride($property, $rawRules, $schema, $normalizedRules);

            if ($propertySchema instanceof FluentSchema) {
                $schema->object()->property($property, $propertySchema);
            } elseif (is_array($propertySchema)) {
                $schema->object()->properties($propertySchema);
            }
        }

        return $schema;
    }

    /*
     * This is a temporary method to allow for overriding the ruleset parsing logic, parseRuleset() method.
     */
    private static function parseRulesetOverride(string $name, array $nestedRuleset, FluentSchema $baseSchema, array $allRules): FluentSchema|array|null
    {
        $validationRules = $nestedRuleset[config('rules-to-schema.validation_rule_token')] ?? [];

        $schemas = [$name => FluentSchema::make()];

        foreach (\LaravelRulesToSchema\Facades\LaravelRulesToSchema::getParsers() as $parserClass) {
            $instance = app($parserClass);

            if (!$instance instanceof LaravelRuleParser) {
                throw new Exception('Rule parsers must implement ' . LaravelRuleParser::class);
            }

            $newSchemas = [];

            foreach ($schemas as $schemaKey => $schema) {
                $resultSchema = $instance($schemaKey, $schema, $validationRules, $nestedRuleset, $baseSchema, $allRules);

                if (null === $resultSchema) {
                    continue;
                }

                if (is_array($resultSchema)) {
                    $newSchemas = [...$newSchemas, ...$resultSchema];
                } else {
                    $newSchemas[$schemaKey] = $resultSchema;
                }
            }

            $schemas = $newSchemas;
        }

        if (0 == count($schemas)) {
            return null;
        } elseif (1 == count($schemas)) {
            return array_values($schemas)[0];
        }

        return $schemas;
    }
}
