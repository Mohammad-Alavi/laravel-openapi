<?php

arch()
    ->coversNothing()
    ->expect('MohammadAlavi\ObjectOrientedJSONSchema')
    ->toOnlyBeUsedIn([
        'MohammadAlavi\ObjectOrientedJSONSchema',
        'MohammadAlavi\ObjectOrientedOpenAPI',
        'MohammadAlavi\LaravelOpenApi',
    ])->ignoring('JSONSchema/Trash')
    ->and('MohammadAlavi\ObjectOrientedOpenAPI')
    ->toOnlyBeUsedIn([
        'MohammadAlavi\ObjectOrientedOpenAPI',
        'MohammadAlavi\LaravelOpenApi',
    ])->and('MohammadAlavi\LaravelOpenApi')
    ->toOnlyBeUsedIn('MohammadAlavi\LaravelOpenApi');
