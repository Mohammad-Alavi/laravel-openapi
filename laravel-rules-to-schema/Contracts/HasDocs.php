<?php

namespace MohammadAlavi\LaravelRulesToSchema\Contracts;

use MohammadAlavi\LaravelRulesToSchema\RuleDocumentation;

interface HasDocs
{
    public function docs(): RuleDocumentation;
}
