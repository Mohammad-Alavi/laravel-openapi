<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface ContentMediaType
{
    public function contentMediaType(string $mediaType): static;
}
