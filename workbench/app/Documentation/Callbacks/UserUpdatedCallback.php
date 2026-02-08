<?php

namespace Workbench\App\Documentation\Callbacks;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Request\RequestQueryExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use Workbench\App\Documentation\WorkbenchCollection;

#[Collection(WorkbenchCollection::class)]
class UserUpdatedCallback extends CallbackFactory implements ShouldBeReferenced
{
    public function component(): Callback
    {
        return Callback::create(
            RequestQueryExpression::create('callbackUrl'),
            PathItem::create()
                ->operations(
                    AvailableOperation::create(
                        HttpMethod::POST,
                        Operation::create()
                            ->requestBody(
                                RequestBody::create(
                                    ContentEntry::json(
                                        MediaType::create()
                                            ->schema(
                                                Schema::object()
                                                    ->description('Request body for User Updated callback')
                                                    ->properties(
                                                        Property::create(
                                                            'id',
                                                            Schema::string()
                                                                ->description('The ID of the updated user')
                                                                ->format(StringFormat::UUID),
                                                        ),
                                                        Property::create(
                                                            'changes',
                                                            Schema::object()
                                                                ->description('The changes made to the user')
                                                                ->properties(
                                                                    Property::create(
                                                                        'name',
                                                                        Schema::string()
                                                                            ->description('The updated name of the user'),
                                                                    ),
                                                                    Property::create(
                                                                        'email',
                                                                        Schema::string()
                                                                            ->description('The updated email of the user')
                                                                            ->format(StringFormat::EMAIL),
                                                                    ),
                                                                    Property::create(
                                                                        'updated_at',
                                                                        Schema::string()
                                                                            ->description('The timestamp when the user was updated')
                                                                            ->format(StringFormat::DATE_TIME),
                                                                    ),
                                                                ),
                                                        ),
                                                    ),
                                            ),
                                    ),
                                )->description('Callback for User Updated'),
                            )->responses(
                                Responses::create(
                                    ResponseEntry::create(
                                        HTTPStatusCode::ok(),
                                        Response::create()->description('OK'),
                                    ),
                                ),
                            ),
                    ),
                ),
        );
    }
}
