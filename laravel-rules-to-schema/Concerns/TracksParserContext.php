<?php

namespace MohammadAlavi\LaravelRulesToSchema\Concerns;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

trait TracksParserContext
{
    protected LooseFluentDescriptor|null $baseSchema = null;

    /** @var array<string, \MohammadAlavi\LaravelRulesToSchema\NestedRuleset>|null */
    protected array|null $allRules = null;

    protected string|null $request = null;

    protected LooseFluentDescriptor|null $modifiedBase = null;

    public function withContext(LooseFluentDescriptor $baseSchema, array $allRules, string|null $request): static
    {
        $clone = clone $this;
        $clone->baseSchema = $baseSchema;
        $clone->allRules = $allRules;
        $clone->request = $request;
        $clone->modifiedBase = null;

        return $clone;
    }

    public function modifiedBaseSchema(): LooseFluentDescriptor|null
    {
        return $this->modifiedBase;
    }
}
