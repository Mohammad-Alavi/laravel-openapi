<?php

namespace MohammadAlavi\Laragen\Support;

use FluentJsonSchema\FluentSchema;
use Illuminate\Foundation\Http\FormRequest;
use LaravelRulesToSchema\Contracts\RuleParser;
use LaravelRulesToSchema\LaravelRulesToSchema;
use LaravelRulesToSchema\ValidationRuleNormalizer;
use Mockery\Exception;

final class RulesToSchema extends LaravelRulesToSchema
{
    public function parse(array|string $rules): FluentSchema
    {
        if (is_string($rules)) {
            if (!class_exists($rules)) {
                throw new \Exception("Class $rules does not implement " . FormRequest::class . ' and can not be parsed.');
            }
            $instance = new $rules();

            $rules = method_exists($instance, 'rules') ? app()->call([$instance, 'rules']) : [];
        }

        $normalizedRules = (new ValidationRuleNormalizer($rules))->getRules();

        $schema = FluentSchema::make()
            ->type()->object()
            ->return();

        foreach ($normalizedRules as $property => $rawRules) {
            $propertySchema = $this->parseRulesetOverride($property, $rawRules, $schema, $normalizedRules);

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
    public function parseRulesetOverride(string $name, array $nestedRuleset, FluentSchema $baseSchema, array $allRules): FluentSchema|array|null
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
