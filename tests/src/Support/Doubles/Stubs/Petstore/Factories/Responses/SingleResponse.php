<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Factories\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

final class SingleResponse implements ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                Response::create(
                    'Unprocessable Entity',
                )->content(
                    ContentEntry::json(
                        MediaType::create()->schema(
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
