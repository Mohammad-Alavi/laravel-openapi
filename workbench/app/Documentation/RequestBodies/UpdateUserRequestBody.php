<?php

namespace Workbench\App\Documentation\RequestBodies;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

final class UpdateUserRequestBody extends RequestBodyFactory
{
    public function component(): RequestBody
    {
        return RequestBody::create()
            ->description('Update User Request Body')
            ->content(
                ContentEntry::json(
                    MediaType::create()
                        ->schema(
                            Schema::object()
                                ->description('Request body for updating a user')
                                ->properties(
                                    Property::create(
                                        'name',
                                        Schema::string()
                                            ->description('The name of the user')
                                            ->minLength(3)
                                            ->maxLength(20),
                                    ),
                                    Property::create(
                                        'password',
                                        Schema::string()
                                            ->description('The password of the user')
                                            ->format(StringFormat::PASSWORD),
                                    ),
                                    Property::create(
                                        'confirm_password',
                                        Schema::string()
                                            ->description('The confirmation of the user\'s password')
                                            ->format(StringFormat::PASSWORD),
                                    ),
                                ),
                        ),
                ),
            );
    }
}
