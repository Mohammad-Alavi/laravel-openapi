<?php

namespace Workbench\App\Documentation\RequestBodies;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

final class CreateUserRequestBody extends RequestBodyFactory
{
    public function component(): RequestBody
    {
        return RequestBody::create()
            ->description('Create User Request Body')
            ->content(
                ContentEntry::json(
                    MediaType::create()
                        ->schema(
                            Schema::object()
                                ->description('Request body for creating a user')
                                ->properties(
                                    Property::create(
                                        'name',
                                        Schema::string()
                                            ->description('The name of the user')
                                            ->minLength(3)
                                            ->maxLength(20),
                                    ),
                                    Property::create(
                                        'email',
                                        Schema::string()
                                            ->description('The email of the user'),
                                    ),
                                    Property::create(
                                        'password',
                                        Schema::string()
                                            ->description('The password of the user')
                                            ->format(StringFormat::PASSWORD),
                                    ),
                                )->required('name', 'email', 'password'),
                        ),
                ),
            );
    }
}
