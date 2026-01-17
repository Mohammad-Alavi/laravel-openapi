<?php

namespace Tests\src\Support\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

class TestRequestBodyFactory extends RequestBodyFactory
{
    public function component(): RequestBody
    {
        return RequestBody::create(
            ContentEntry::json(MediaType::create()),
        );
    }
}
