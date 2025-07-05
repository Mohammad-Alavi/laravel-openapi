<?php

use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/config',
        __DIR__ . '/JSONSchema',
        __DIR__ . '/oooapi',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->in(__DIR__ . '/workbench')->exclude(['public', 'storage', 'vendor'])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'concat_space' => ['spacing' => 'one'],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
        ],
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try'],
        ],
        'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => true],
        'nullable_type_declaration' => ['syntax' => 'union'],
    ])
    ->setFinder($finder);
