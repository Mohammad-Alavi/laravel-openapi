<?php

namespace MohammadAlavi\Laragen\Support;

use FluentJsonSchema\FluentSchema;
use Illuminate\Foundation\Http\FormRequest;
use LaravelRulesToSchema\LaravelRulesToSchema;
use LaravelRulesToSchema\ValidationRuleNormalizer;

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
            $propertySchema = $this->parseRuleset($property, $rawRules, $schema, $normalizedRules);

            if ($propertySchema instanceof FluentSchema) {
                $schema->object()->property($property, $propertySchema);
            } elseif (is_array($propertySchema)) {
                $schema->object()->properties($propertySchema);
            }
        }

        return $schema;
    }
}
