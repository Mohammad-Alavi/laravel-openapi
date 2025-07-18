<?php

namespace Workbench\App\Documentation\Responses;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

final class UserResponse extends ResponseFactory implements ShouldBeReferenced
{
    public function component(): Response
    {
        return Response::create(
            'UserResponse',
        )->content(
            ContentEntry::json(
                MediaType::create()->schema(
                    Schema::object()
                        ->description('Generic response for a user object')
                        ->properties(
                            Property::create(
                                'id',
                                Schema::string()
                                    ->description('The unique identifier of the user')
                                    ->format(StringFormat::UUID),
                            ),
                            Property::create(
                                'phone',
                                Schema::integer()
                                    ->description('The phone number of the user'),
                            ),
                            Property::create(
                                'name',
                                Schema::string()
                                    ->description('The name of the user'),
                            ),
                            Property::create(
                                'email',
                                Schema::string()
                                    ->description('The email address of the user')
                                    ->format(StringFormat::EMAIL),
                            ),
                        ),
                ),
            ),
        );
    }
}
