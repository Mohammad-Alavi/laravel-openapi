<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Reusable\Response;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

final class ValidationErrorResponse extends ResponseFactory implements ShouldBeReferenced
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
            ContentEntry::json(
                MediaType::create()->schema($objectDescriptor),
            ),
        );
    }
}
