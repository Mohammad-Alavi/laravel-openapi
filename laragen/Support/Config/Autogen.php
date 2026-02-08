<?php

namespace MohammadAlavi\Laragen\Support\Config;

final readonly class Autogen
{
    public function requestBody(): bool
    {
        return config('laragen.autogen.request_body');
    }

    public function example(): bool
    {
        return config('laragen.autogen.example');
    }

    public function security(): bool
    {
        return config('laragen.autogen.security');
    }
}
