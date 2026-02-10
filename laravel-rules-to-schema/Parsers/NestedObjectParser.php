<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class NestedObjectParser implements RuleParser
{
    /** @var callable(string, NestedRuleset): (LooseFluentDescriptor|array|null) */
    private $parseRuleset;

    /** @param callable(string, NestedRuleset): (LooseFluentDescriptor|array|null) $parseRuleset */
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
        NestedRuleset $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        if (!$nestedRuleset->hasChildren()) {
            return $schema;
        }

        if (null === $this->parseRuleset) {
            return $schema;
        }

        if ($nestedRuleset->hasWildcardChild()) {
            $objSchema = ($this->parseRuleset)("{$attribute}.*", $nestedRuleset->children['*']);

            if ($objSchema instanceof LooseFluentDescriptor) {
                $schema = $schema->type(Type::array())->items($objSchema);
            }
        } else {
            $properties = [];
            foreach ($nestedRuleset->children as $propName => $childRuleset) {
                $propSchema = ($this->parseRuleset)($propName, $childRuleset);

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
