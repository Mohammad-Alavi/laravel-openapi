<?php

namespace Workbench\App\Documentation\Shared\Responses;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

final class UnprocessableEntityResponse extends ResponseFactory implements ShouldBeReferenced
{
    public function component(): Response
    {
        return Response::create(
            'Unprocessable Entity',
        )->content(
            ContentEntry::json(
                MediaType::create()->schema(
                    Schema::object()
                    ->properties(
                        Property::create(
                            'message',
                            Schema::string()->description('A human-readable message describing the error.'),
                        ),
                        Property::create(
                            'errors',
                            Schema::object()->additionalProperties(
                                Schema::array()->items(Schema::string()),
                            )->description('A map of field names to validation errors.'),
                        ),
                    ),
                ),
            ),
        );
    }
}
