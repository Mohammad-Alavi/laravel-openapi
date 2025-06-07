<?php

namespace Tests\oooas\Integration;

use Illuminate\Support\Facades\File;
use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Collections\Path;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Description as InfoDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\TermsOfService;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Name as LicenseName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\URL as LicenseURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name as ParamName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL as ServerURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use Tests\oooas\Doubles\Stubs\ReusableSchemaStub;

describe('PetStoreTest', function (): void {
    test('PetStore Example', function (): void {
        $contact = Contact::create()
            ->name(Name::create('Swagger API Team'))
            ->email(Email::create('apiteam@swagger.io'))
            ->url(URL::create('https://swagger.io'));

        $license = License::create(LicenseName::create('Apache 2.0'))
            ->url(LicenseURL::create('https://www.apache.org/licenses/LICENSE-2.0.html'));

        $server = Server::create(ServerURL::create('https://petstore.swagger.io/api'));

        $info = Info::create(
            Title::create('Swagger Petstore'),
            Version::create('1.0.0'),
        )->description(
            InfoDescription::create(
                'A sample API that uses a petstore as an example to demonstrate features in the OpenAPI 3.0 specification',
            ),
        )->termsOfService(TermsOfService::create('https://swagger.io/terms/'))
            ->contact($contact)
            ->license($license);

        $tagsParameter = Parameter::query(
            ParamName::create('tags'),
            SchemaSerializedQuery::create(
                Schema::array()->items(
                    Schema::string(),
                ),
                Form::create(),
            ),
        )->description(Description::create('tags to filter by'));

        $limitParameter = Parameter::query(
            ParamName::create('limit'),
            SchemaSerializedQuery::create(
                Schema::integer()->format(IntegerFormat::INT32),
            ),
        )->description(Description::create('maximum number of results to return'));

        $allOf = Schema::object()
            ->allOf(
                ReusableSchemaStub::create()->build(),
                Schema::object()
                    ->required('id')
                    ->properties(
                        Property::create(
                            'id',
                            Schema::integer()->format(IntegerFormat::INT64),
                        ),
                    ),
            );

        $newPetSchema = new class extends ReusableSchemaFactory {
            public function build(): JSONSchema
            {
                return Schema::object()
                    ->required('name')
                    ->properties(
                        Property::create(
                            'name',
                            Schema::string(),
                        ),
                        Property::create(
                            'tag',
                            Schema::string(),
                        ),
                    );
            }

            public static function key(): string
            {
                return 'NewPet';
            }
        };

        $errorSchema = new class extends ReusableSchemaFactory {
            public function build(): JSONSchema
            {
                return Schema::object()
                    ->required('code', 'message')
                    ->properties(
                        Property::create(
                            'code',
                            Schema::integer()->format(IntegerFormat::INT32),
                        ),
                        Property::create(
                            'message',
                            Schema::string(),
                        ),
                    );
            }

            public static function key(): string
            {
                return 'Error';
            }
        };

        $components = Components::create()
            ->schemas($allOf, $newPetSchema, $errorSchema);

        $petResponse = Response::ok('pet response')
            ->content(
                MediaType::json()->schema(
                    $allOf,
                ),
            );

        $petListingResponse = Response::ok('pet response')
            ->content(
                MediaType::json()->schema(
                    Schema::array()->items(
                        $allOf,
                    ),
                ),
            );

        $defaultErrorResponse = Response::internalServerError('unexpected error')
            ->content(MediaType::json()->schema(
                $errorSchema::create()->build(),
            ));

        $operation = Operation::get()
            ->description("Returns all pets from the system that the user has access to\nNam sed condimentum est. Maecenas tempor sagittis sapien, nec rhoncus sem sagittis sit amet. Aenean at gravida augue, ac iaculis sem. Curabitur odio lorem, ornare eget elementum nec, cursus id lectus. Duis mi turpis, pulvinar ac eros ac, tincidunt varius justo. In hac habitasse platea dictumst. Integer at adipiscing ante, a sagittis ligula. Aenean pharetra tempor ante molestie imperdiet. Vivamus id aliquam diam. Cras quis velit non tortor eleifend sagittis. Praesent at enim pharetra urna volutpat venenatis eget eget mauris. In eleifend fermentum facilisis. Praesent enim enim, gravida ac sodales sed, placerat id erat. Suspendisse lacus dolor, consectetur non augue vel, vehicula interdum libero. Morbi euismod sagittis libero sed lacinia.\n\nSed tempus felis lobortis leo pulvinar rutrum. Nam mattis velit nisl, eu condimentum ligula luctus nec. Phasellus semper velit eget aliquet faucibus. In a mattis elit. Phasellus vel urna viverra, condimentum lorem id, rhoncus nibh. Ut pellentesque posuere elementum. Sed a varius odio. Morbi rhoncus ligula libero, vel eleifend nunc tristique vitae. Fusce et sem dui. Aenean nec scelerisque tortor. Fusce malesuada accumsan magna vel tempus. Quisque mollis felis eu dolor tristique, sit amet auctor felis gravida. Sed libero lorem, molestie sed nisl in, accumsan tempor nisi. Fusce sollicitudin massa ut lacinia mattis. Sed vel eleifend lorem. Pellentesque vitae felis pretium, pulvinar elit eu, euismod sapien.\n")
            ->operationId('findPets')
            ->parameters(ParameterCollection::create($tagsParameter, $limitParameter))
            ->responses(Responses::create($petListingResponse, $defaultErrorResponse));

        $addPet = Operation::post()
            ->description('Creates a new pet in the store.  Duplicates are allowed')
            ->operationId('addPet')
            ->requestBody(
                RequestBody::create()
                    ->description('Pet to add to the store')
                    ->required()
                    ->content(
                        MediaType::json()->schema(
                            $newPetSchema::create()->build(),
                        ),
                    ),
            )
            ->responses(Responses::create($petResponse, $defaultErrorResponse));

        $path = Path::create(
            '/pets',
            PathItem::create()
                ->operations($operation, $addPet),
        );

        $petIdParameter = Parameter::path(
            ParamName::create('id'),
        )->schema(
            Schema::integer()->format(IntegerFormat::INT64),
        )->description(Description::create('ID of pet to fetch'))
            ->required();

        $findPetById = Operation::get()
            ->description('Returns a user based on a single ID, if the user does not have access to the pet')
            ->operationId('find pet by id')
            ->parameters(ParameterCollection::create($petIdParameter))
            ->responses(Responses::create($petResponse, $defaultErrorResponse));

        $petDeletedResponse = Response::deleted('pet deleted');

        $deletePetById = Operation::delete()
            ->description('deletes a single pet based on the ID supplied')
            ->operationId('deletePet')
            ->parameters(ParameterCollection::create($petIdParameter->description(Description::create('ID of pet to delete'))))
            ->responses(Responses::create($petDeletedResponse, $defaultErrorResponse));

        $petNested = Path::create(
            '/pets/{id}',
            PathItem::create()
                ->operations($findPetById, $deletePetById),
        );

        $openApi = OpenAPI::v311($info)
            ->servers($server)
            ->paths(Paths::create($path, $petNested))
            ->components($components);

        $this->assertEquals(
            File::json(realpath(__DIR__ . '/../Doubles/Stubs/petstore_expanded.json')),
            $openApi->asArray(),
        );
    });
})->coversNothing();
