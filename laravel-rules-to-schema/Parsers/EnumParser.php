<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use Illuminate\Validation\Rules\Enum as EnumRule;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use ReflectionClass;

final readonly class EnumParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->rule instanceof EnumRule) {
                $enumType = invade($validationRule->rule)->type; /** @phpstan-ignore property.protected */
                $reflection = new ReflectionClass($enumType);

                if (count($reflection->getConstants()) > 0) {
                    $values = array_values(array_map(
                        static fn (\UnitEnum|\BackedEnum $c): string|int => $c instanceof \BackedEnum ? $c->value : $c->name,
                        $reflection->getConstants(),
                    ));

                    $schema = $schema->enum(...$values);
                }
            }
        }

        return $schema;
    }
}
