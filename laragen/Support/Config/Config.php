<?php

namespace MohammadAlavi\Laragen\Support\Config;

final readonly class Config
{
    public function enabled(): bool
    {
        return config('laragen.enabled');
    }

    public function generator(): string
    {
        return config('laragen.generator');
    }

    public function autogen(): Autogen
    {
        return new Autogen();
    }
}
