<?php

use LaravelRulesToSchema\Parsers\ConfirmedParser;
use LaravelRulesToSchema\Parsers\CustomRuleSchemaParser;
use LaravelRulesToSchema\Parsers\EnumParser;
use LaravelRulesToSchema\Parsers\ExcludedParser;
use LaravelRulesToSchema\Parsers\FormatParser;
use LaravelRulesToSchema\Parsers\MiscPropertyParser;
use LaravelRulesToSchema\Parsers\NestedObjectParser;
use LaravelRulesToSchema\Parsers\RequiredParser;
use LaravelRulesToSchema\Parsers\TypeParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\AcceptedDeclinedParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\AdditionalConstraintParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ComparisonConstraintParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ConditionalAcceptedParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ConditionalExcludeParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ConditionalProhibitedParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ConditionalRequiredParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\CustomRuleDocsParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ExampleOverride;
use MohammadAlavi\Laragen\RequestSchema\Parsers\FileUploadParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\NotInParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\NumericConstraintParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\PasswordParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\PresentFieldParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\RequiredWithParser;
use MohammadAlavi\Laragen\RequestSchema\Parsers\StringPatternParser;

return [
    /*
     * The internal key to store validation rules under for parsers
     * This should be unique and not match any real property names
     * that will be submitted in requests.
     */
    'validation_rule_token' => '##_VALIDATION_RULES_##',

    /*
     * The parsers to run rules through
     */
    'parsers' => [
        TypeParser::class,
        NestedObjectParser::class,
        RequiredParser::class,
        MiscPropertyParser::class,
        FormatParser::class,
        EnumParser::class,
        ExcludedParser::class,
        ConfirmedParser::class,
        CustomRuleSchemaParser::class,
        CustomRuleDocsParser::class,
        FileUploadParser::class,
        PasswordParser::class,
        StringPatternParser::class,
        ComparisonConstraintParser::class,
        NumericConstraintParser::class,
        NotInParser::class,
        AcceptedDeclinedParser::class,
        AdditionalConstraintParser::class,
        ExampleOverride::class,
        RequiredWithParser::class,
        // RequiredWithoutParser — not registered; has known issue with nullable field restructuring
        ConditionalRequiredParser::class,
        ConditionalExcludeParser::class,
        ConditionalProhibitedParser::class,
        PresentFieldParser::class,
        ConditionalAcceptedParser::class,
    ],

    /*
     * Third party rules that you can provide custom schema definitions for
     */
    'custom_rule_schemas' => [
        // \CustomPackage\CustomRule::class => \Support\CustomRuleSchemaDefinition::class,
        // \CustomPackage\CustomRule::class => 'string',
        // \CustomPackage\CustomRule::class => ['null', 'string'],
    ],
];
