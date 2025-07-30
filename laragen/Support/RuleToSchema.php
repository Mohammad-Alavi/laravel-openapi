<?php

namespace MohammadAlavi\Laragen\Support;

use FluentJsonSchema\FluentSchema;
use Illuminate\Foundation\Http\FormRequest;
use LaravelRulesToSchema\Contracts\RuleParser;
use LaravelRulesToSchema\LaravelRulesToSchema;
use LaravelRulesToSchema\ValidationRuleNormalizer;
use Mockery\Exception;
use MohammadAlavi\Laragen\RuleParsers\RequiredWith;

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

        $ruleSets = (new ValidationRuleNormalizer($rule))->getRules();

        $schema = FluentSchema::make()
            ->type()->object()
            ->return();

        foreach ($ruleSets as $property => $rawRules) {
            $propertySchema = self::parseRulesetOverride($property, $rawRules);

            if ($propertySchema instanceof FluentSchema) {
                $schema->object()->property($property, $propertySchema);
            } elseif (is_array($propertySchema)) {
                $schema->object()->properties($propertySchema);
            }
        }

        foreach ($ruleSets as $property => $rawRules) {
            $propertySchema = self::parseCustomRules($property, $rawRules, $schema, $ruleSets);

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
    private static function parseRulesetOverride(string $name, array $nestedRuleset): FluentSchema|array|null
    {
        $validationRules = $nestedRuleset[config('rules-to-schema.validation_rule_token')] ?? [];

        $schemas = [$name => FluentSchema::make()];

        foreach (\LaravelRulesToSchema\Facades\LaravelRulesToSchema::getParsers() as $parserClass) {
            $instance = app($parserClass);

            if (!$instance instanceof RuleParser) {
                throw new Exception('Rule parsers must implement ' . RuleParser::class);
            }

            $newSchemas = [];

            foreach ($schemas as $schemaKey => $schema) {
                $resultSchema = $instance($schemaKey, $schema, $validationRules, $nestedRuleset);

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

    private static function parseCustomRules(string $name, array $nestedRuleset, FluentSchema $baseSchema, array $ruleSets): FluentSchema|array|null
    {
        $validationRules = $nestedRuleset[config('rules-to-schema.validation_rule_token')] ?? [];

        $schemas = [$name => FluentSchema::make()];

        $newSchemas = [];

        foreach ($schemas as $schemaKey => $schema) {
            app(RequiredWith::class)($schemaKey, $schema, $validationRules, $nestedRuleset, $baseSchema, $ruleSets);
        }

        $schemas = $newSchemas;

        if (0 == count($schemas)) {
            return null;
        } elseif (1 == count($schemas)) {
            return array_values($schemas)[0];
        }

        return $schemas;
    }
}
