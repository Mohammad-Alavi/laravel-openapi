<?php

namespace Workbench\App\Documentation\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

final readonly class UserResponse implements ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create(
                    'UserResponse',
                )->content(
                    ContentEntry::json(
                        MediaType::create()->schema(
                            Schema::object()
                                ->description('Response for creating or updating a user')
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
                                )->required('id', 'name', 'email'),
                        ),
                    ),
                ),
            ),
        );
    }
}
