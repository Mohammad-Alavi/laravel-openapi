<?php

namespace MohammadAlavi\Laragen\Support;

use FluentJsonSchema\FluentSchema;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use LaravelRulesToSchema\Contracts\RuleParser;
use LaravelRulesToSchema\LaravelRulesToSchema;
use LaravelRulesToSchema\ValidationRuleNormalizer;
use Mockery\Exception;
use MohammadAlavi\Laragen\RuleParsers\ExampleOverride;
use MohammadAlavi\Laragen\RuleParsers\RequiredWithoutParser;
use MohammadAlavi\Laragen\RuleParsers\RequiredWithParser;
use Webmozart\Assert\Assert;

final class RuleToSchema extends LaravelRulesToSchema
{
    public static function transform(array|string|Route $rule): FluentSchema
    {
        $request = null;
        if (is_string($rule)) {
            Assert::isAOf(
                $rule,
                FormRequest::class,
                "Class {$rule} does not implement " . FormRequest::class . ' and can not be parsed.',
            );
            $request = $rule;
            $instance = new $rule();

            $rule = method_exists($instance, 'rules') ? app()->call([$instance, 'rules']) : [];
        }

        if ($rule instanceof Route) {
            $route = $rule;
            $extractor = app(RuleExtractor::class);

            $request = $extractor->getFormRequestInstance($route);
            $request = $request ? get_class($request) : null;

            $rule = $extractor->extractFrom($route);
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
            $propertySchema = self::parseCustomRules($property, $rawRules, $schema, $ruleSets, $request);

            if ($propertySchema instanceof FluentSchema) {
                $schema->object()->property($property, $propertySchema);
            } elseif (is_array($propertySchema)) {
                $schema->object()->properties($propertySchema);
            }
        }

        return self::distinctRequired($schema);
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

    private static function parseCustomRules(string $name, array $nestedRuleset, FluentSchema $baseSchema, array $ruleSets, string|null $request): FluentSchema|array|null
    {
        $validationRules = $nestedRuleset[config('rules-to-schema.validation_rule_token')] ?? [];

        $schemas = [$name => $baseSchema->getSchemaDTO()->properties[$name] ?? FluentSchema::make()];

        $newSchemas = [];

        foreach ($schemas as $attribute => $schema) {
            $resultSchema = app(ExampleOverride::class)($attribute, $schema, $validationRules, $nestedRuleset, $baseSchema, $ruleSets, $request);
            //            app(RequiredWithoutParser::class)($attribute, $schema, $validationRules, $nestedRuleset, $baseSchema, $ruleSets);
            //            app(RequiredWithParser::class)($attribute, $schema, $validationRules, $nestedRuleset, $baseSchema, $ruleSets);

            if (null === $resultSchema) {
                continue;
            }

            if (is_array($resultSchema)) {
                $newSchemas = [...$newSchemas, ...$resultSchema];
            } else {
                $newSchemas[$attribute] = $resultSchema;
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

    private static function distinctRequired(FluentSchema $schema): FluentSchema
    {
        $schema->getSchemaDTO()->required = array_values(array_unique($schema->getSchemaDTO()->required));

        return $schema;
    }
}
