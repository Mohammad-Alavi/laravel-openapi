<?php

namespace Tests\Doubles\Stubs\Petstore\Factories\Responses;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final class SingleResponse extends ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                Response::create(
                    Description::create('Unprocessable Entity'),
                )->content(
                    ContentEntry::create(
                        'application/json',
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
                ),
            ),
        );
    }
}
