<?php

use MohammadAlavi\LaravelRulesToSchema\Parsers\AcceptedDeclinedParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\AdditionalConstraintParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ComparisonConstraintParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalAcceptedParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalExcludeParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalProhibitedParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalRequiredParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConfirmedParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\CustomRuleDocsParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\CustomRuleSchemaParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\EnumParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ExampleOverride;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ExcludedParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\FileUploadParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\FormatParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\MiscPropertyParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\NestedObjectParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\NotInParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\NumericConstraintParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\PasswordParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\PresentFieldParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\RequiredParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\RequiredWithoutParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\RequiredWithParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\StringPatternParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\TypeParser;

return [
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
        RequiredWithoutParser::class,
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
