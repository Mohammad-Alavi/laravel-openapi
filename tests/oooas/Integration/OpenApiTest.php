<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
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
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use Workbench\App\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Workbench\App\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;

describe('OpenApi', function (): void {
    it('can generate valid OpenAPI v3.1.0 docs', function (): void {
        $tag = Tag::create('Audits')->description('All the audits');
        $contact = Contact::create()
            ->name('Example')
            ->url('https://laragen.io')
            ->email('hello@laragen.io');
        $info = Info::create('API Specification', 'v1')
            ->description('For using the Example App API')
            ->contact($contact)
            ->license(
                License::create('MIT')->url('https://github.com/laragen'),
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
                Response::create('OK')
                    ->content(
                        ContentEntry::json(
                            MediaType::create()->schema($schema),
                        ),
                    ),
            ),
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                Response::create('Unprocessable Entity')
                    ->content(
                        ContentEntry::json(
                            MediaType::create()->schema($schema),
                        ),
                    ),
            ),
        );
        $indexOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary('List all audits')
            ->operationId('audits.index');
        $postOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary('Create an audit')
            ->operationId('audits.store')
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
            ->summary('View an audit')
            ->operationId('audits.show')
            ->parameters(
                Parameters::create(
                    Parameter::path(
                        'audit',
                        PathParameter::create($stringDescriptor),
                    )->required(),
                    Parameter::query(
                        'format',
                        QueryParameter::create($enumDescriptor),
                    )->description('The format of the appointments'),
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
            Server::create('https://api.laragen.io/v1'),
            Server::create('https://api.laragen.io/v2'),
        ];
        $components = Components::create()->securitySchemes(TestBearerSecuritySchemeFactory::create());
        $security = Security::create(TestBearerSecurityRequirementFactory::create());
        $externalDocumentation = ExternalDocumentation::create('https://laragen.io/docs')
            ->description(
                'Example',
            );
        $openApi = OpenAPI::v311($info)
            ->paths($paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocumentation);

        $openApi->toJsonFile('openapi', options: JSON_PRETTY_PRINT);

        expect('openapi.json')->toBeValidJsonSchema();
        $this->pushCleanupCallback(fn () => \Safe\unlink('openapi.json'));
    });
})->coversNothing();
