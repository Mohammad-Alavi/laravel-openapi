<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface ContentEncoding
{
    public function contentEncoding(string $encoding): static;
}
