<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRuleNormalizer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class NestedObjectParser implements RuleParser
{
    /** @var callable(string, array): (LooseFluentDescriptor|array|null) */
    private $parseRuleset;

    /** @param callable(string, array): (LooseFluentDescriptor|array|null) $parseRuleset */
    public function withParseRuleset(callable $parseRuleset): static
    {
        $clone = clone $this;
        $clone->parseRuleset = $parseRuleset;

        return $clone;
    }

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        $nestedObjects = array_filter(
            $nestedRuleset,
            static fn ($x) => ValidationRuleNormalizer::RULES_KEY !== $x,
            ARRAY_FILTER_USE_KEY,
        );

        if (0 === count($nestedObjects)) {
            return $schema;
        }

        if (null === $this->parseRuleset) {
            return $schema;
        }

        $isArray = array_key_exists('*', $nestedObjects);

        if ($isArray) {
            $objSchema = ($this->parseRuleset)("{$attribute}.*", $nestedObjects['*']);

            if ($objSchema instanceof LooseFluentDescriptor) {
                $schema = $schema->type(Type::array())->items($objSchema);
            }
        } else {
            $properties = [];
            foreach ($nestedObjects as $propName => $objValidationRules) {
                $propSchema = ($this->parseRuleset)($propName, $objValidationRules);

                if ($propSchema instanceof LooseFluentDescriptor) {
                    $properties[] = Property::create($propName, $propSchema);
                }
            }

            if ([] !== $properties) {
                $schema = $schema->type(Type::object())->properties(...$properties);
            }
        }

        return $schema;
    }
}
