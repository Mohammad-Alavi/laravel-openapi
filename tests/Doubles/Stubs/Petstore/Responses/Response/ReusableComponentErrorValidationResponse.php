<?php

namespace Tests\Doubles\Stubs\Petstore\Responses\Response;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class ReusableComponentErrorValidationResponse extends ReusableResponseFactory
{
    public function build(): Response
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

        return Response::unprocessableEntity()
            ->content(
                MediaType::json()->schema($objectDescriptor),
            );
    }
}
