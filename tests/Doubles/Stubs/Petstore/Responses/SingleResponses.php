<?php

namespace Tests\Doubles\Stubs\Petstore\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;

final class SingleResponses extends ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            Response::unprocessableEntity()
            ->content(
                MediaType::json()->schema(
                    Schema::object()
                    ->properties(
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
                    ),
                ),
            ),
        );
    }
}
