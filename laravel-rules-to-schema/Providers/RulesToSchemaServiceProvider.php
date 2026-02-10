<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Providers;

use Illuminate\Support\ServiceProvider;
use MohammadAlavi\LaravelRulesToSchema\CustomRuleSchemaMapping;
use MohammadAlavi\LaravelRulesToSchema\RuleToSchema;

final class RulesToSchemaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/rules-to-schema.php',
            'rules-to-schema',
        );

        $this->app->singleton(RuleToSchema::class, static function (): RuleToSchema {
            $rawMappings = config('rules-to-schema.custom_rule_schemas', []);
            $mappings = array_map(
                static fn (mixed $value): CustomRuleSchemaMapping => $value instanceof CustomRuleSchemaMapping
                    ? $value
                    : CustomRuleSchemaMapping::from($value),
                $rawMappings,
            );

            return new RuleToSchema(
                config('rules-to-schema.parsers', []),
                $mappings,
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/rules-to-schema.php' => config_path('rules-to-schema.php'),
        ], 'rules-to-schema-config');
    }
}
