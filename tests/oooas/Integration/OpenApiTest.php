<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL as ServerURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use Pest\Expectation;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;

afterAll(
    function (): void {
        \Safe\unlink('openapi.json');
    },
);

describe('OpenApi', function (): void {
    it('can generate valid OpenAPI v3.1.0 docs', function (): void {
        $tag = Tag::create(
            Name::create('Audits'),
            Description::create('All the audits'),
        );
        $contact = Contact::create()
            ->name(Name::create('Example'))
            ->url(URL::create('https://laragen.io'))
            ->email(Email::create('hello@laragen.io'));
        $info = Info::create(
            Title::create('API Specification'),
            Version::create('v1'),
        )->description(Description::create('For using the Example App API'))
            ->contact($contact)
            ->license(
                License::create(
                    Name::create('MIT'),
                    URL::create('https://github.com/laragen'),
                ),
            );
        $schema = Schema::object()
            ->properties(
                Property::create('id', Schema::string()->format(StringFormat::UUID)),
                Property::create('created_at', Schema::string()->format(StringFormat::DATE_TIME)),
                Property::create('age', Schema::integer()->examples(60)),
                Property::create(
                    'data',
                    Schema::array()->items(
                        Schema::allOf(
                            Schema::string()->format(StringFormat::UUID),
                        ),
                    ),
                ),
            )->required('id', 'created_at');
        $responses = Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create(
                    Description::create('OK'),
                )->content(
                    ContentEntry::json(
                        MediaType::create()->schema($schema),
                    ),
                ),
            ),
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                Response::create(
                    Description::create('Unprocessable Entity'),
                )->content(
                    ContentEntry::json(
                        MediaType::create()->schema($schema),
                    ),
                ),
            ),
        );
        $indexOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary(Summary::create('List all audits'))
            ->operationId(OperationId::create('audits.index'));
        $postOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary(Summary::create('Create an audit'))
            ->operationId(OperationId::create('audits.store'))
            ->requestBody(
                RequestBody::create()
                    ->content(
                        ContentEntry::json(
                            MediaType::create()->schema($schema),
                        ),
                    ),
            );
        $stringDescriptor = Schema::string()->format(StringFormat::UUID);
        $enumDescriptor = Schema::enum('json', 'ics')
            ->default('json');
        $getOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary(Summary::create('View an audit'))
            ->operationId(OperationId::create('audits.show'))
            ->parameters(
                Parameters::create(
                    Parameter::path(
                        Name::create('audit'),
                        SchemaSerializedPath::create($stringDescriptor),
                    )->required(),
                    Parameter::query(
                        Name::create('format'),
                        SchemaSerializedQuery::create($enumDescriptor),
                    )->description(Description::create('The format of the appointments')),
                ),
            );
        $paths = Paths::create(
            Path::create(
                '/audits',
                PathItem::create()
                    ->operations(
                        AvailableOperation::create(
                            HttpMethod::GET,
                            $indexOperation,
                        ),
                        AvailableOperation::create(
                            HttpMethod::POST,
                            $postOperation,
                        ),
                    ),
            ),
            Path::create(
                '/audits/{audit}',
                PathItem::create()
                    ->operations(
                        AvailableOperation::create(
                            HttpMethod::GET,
                            $getOperation,
                        ),
                    ),
            ),
        );
        $servers = [
            Server::create(ServerURL::create('https://api.laragen.io/v1')),
            Server::create(ServerURL::create('https://api.laragen.io/v2')),
        ];
        $components = Components::create()->securitySchemes(TestBearerSecuritySchemeFactory::create());
        $security = Security::create(TestBearerSecurityRequirementFactory::create());
        $externalDocumentation = ExternalDocumentation::create(
            URL::create('https://laragen.io/docs'),
            Description::create('Example'),
        );
        $openApi = OpenAPI::v311($info)
            ->paths($paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocumentation);

        file_put_contents('openapi.json', $openApi->toJson(JSON_PRETTY_PRINT));

        $output = [];
        $returnVar = 0;
        $successful = exec(
            'npx redocly lint --format stylish --extends recommended-strict openapi.json 2>&1',
            $output,
            $returnVar,
        );

        expect($successful)->unless(
            $successful,
            function (Expectation $expectation) use ($output, $returnVar): Expectation {
                return $expectation->toBeEmpty(implode("\n", $output))
                    ->and($returnVar)->toBe(0);
            },
        );
    });
})->coversNothing();
