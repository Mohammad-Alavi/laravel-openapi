<?php

namespace Tests\Doubles\Stubs\Petstore\Reusable\Response;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class ValidationErrorResponse extends ResponseFactory
{
    public function component(): Response
    {
        $objectDescriptor = Schema::object()->properties(
            Property::create(
                'message',
                Schema::string()->examples('The given data was invalid.'),
            ),
            Property::create(
                'errors',
                Schema::object()->additionalProperties(
                    Schema::array()->items(Schema::string()),
                )->examples(['field' => ['Something is wrong with this field!']]),
            ),
        );

        return Response::create(
            Description::create('Unprocessable Entity'),
        )->content(
            ContentEntry::create(
                'application/json',
                MediaType::json()->schema($objectDescriptor),
            ),
        );
    }
}
