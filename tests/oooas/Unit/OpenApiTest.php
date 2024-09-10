<?php

namespace Tests\oooas\Unit;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\ValidationException;
use MohammadAlavi\ObjectOrientedOAS\Objects\AllOf;
use MohammadAlavi\ObjectOrientedOAS\Objects\Components;
use MohammadAlavi\ObjectOrientedOAS\Objects\Contact;
use MohammadAlavi\ObjectOrientedOAS\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOAS\Objects\Info;
use MohammadAlavi\ObjectOrientedOAS\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOAS\Objects\OAuthFlow;
use MohammadAlavi\ObjectOrientedOAS\Objects\Operation;
use MohammadAlavi\ObjectOrientedOAS\Objects\Parameter;
use MohammadAlavi\ObjectOrientedOAS\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOAS\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOAS\Objects\Response;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;
use MohammadAlavi\ObjectOrientedOAS\Objects\Server;
use MohammadAlavi\ObjectOrientedOAS\Objects\Tag;
use MohammadAlavi\ObjectOrientedOAS\OpenApi;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(OpenApi::class)]
class OpenApiTest extends UnitTestCase
{
        public function test_all_properties_works_and_validation_passes()
    {
        // Create a tag.
        $tag = Tag::create()
            ->name('Audits')
            ->description('All the audits');

        // Factory creation method.
        $contact = Contact::create()
            ->name('GoldSpec Digital')
            ->url('https://goldspecdigital.com')
            ->email('hello@goldspecdigital.com');

        // Alternative method chaining creation method.
        $info = Info::create()
            ->title('API Specification')
            ->version('v1')
            ->description('For using the Example App API')
            ->contact($contact);

        // Create a schema object to be used where a schema is accepted.
        $exampleObject = Schema::object()
            ->properties(
                Schema::string('id')->format(Schema::FORMAT_UUID),
                Schema::string('created_at')->format(Schema::FORMAT_DATE_TIME),
                Schema::integer('age')->example(60),
                Schema::array('data')->items(
                    AllOf::create()->schemas(
                        Schema::string('id')->format(Schema::FORMAT_UUID)
                    )
                )
            )
            ->required('id', 'created_at');

        // A response to be returned from a route.
        $exampleResponse = Response::create()
            ->statusCode(200)
            ->description('OK')
            ->content(
                MediaType::json()->schema($exampleObject)
            );

        // Create an operation for a route.
        $listAudits = Operation::get()
            ->responses($exampleResponse)
            ->tags($tag)
            ->summary('List all audits')
            ->operationId('audits.index');

        // Create an operation for a route.
        $createAudit = Operation::post()
            ->responses($exampleResponse)
            ->tags($tag)
            ->summary('Create an audit')
            ->operationId('audits.store')
            ->requestBody(RequestBody::create()->content(
                MediaType::json()->schema($exampleObject)
            ));

        // Create parameter schemas.
        $auditId = Schema::string('audit')
            ->format(Schema::FORMAT_UUID);
        $format = Schema::string('format')
            ->enum('json', 'ics')
            ->default('json');

        // Create an operation for a route.
        $readAudit = Operation::get()
            ->responses($exampleResponse)
            ->tags($tag)
            ->summary('View an audit')
            ->operationId('audits.show')
            ->parameters(
                Parameter::path()
                    ->name('audit')
                    ->schema($auditId)
                    ->required(),
                Parameter::query()
                    ->name('format')
                    ->schema($format)
                    ->description('The format of the appointments')
            );

        // Specify the paths supported by the API.
        $paths = [
            // Create a path along with it's operations.
            PathItem::create()
                ->route('/audits')
                ->operations($listAudits, $createAudit),
            PathItem::create()
                ->route('/audits/{audit}')
                ->operations($readAudit),
        ];

        // Specify the server endpoints.
        $servers = [
            Server::create()->url('https://api.example.com/v1'),
            Server::create()->url('https://api.example.com/v2'),
        ];

        // Create a security scheme component.
        $authFlow = OAuthFlow::create()
            ->flow(OAuthFlow::FLOW_PASSWORD)
            ->tokenUrl('https://api.example.com/oauth/authorize');

        $securityScheme = SecurityScheme::oauth2('OAuth2')
            ->flows($authFlow);

        $components = Components::create()->securitySchemes($securityScheme);

        // Specify the security.
        $security = SecurityRequirement::create()->securityScheme($securityScheme);

        // Specify external documentatino for the API.
        $externalDocs = ExternalDocs::create()
            ->url('https://github.com/goldspecdigital/oooas')
            ->description('GitHub Wiki');

        // Create the main OpenAPI object composed off everything created above.
        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_1)
            ->info($info)
            ->paths(...$paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocs);

        $exampleResponse = file_get_contents(realpath(__DIR__ . '/../Stubs/example_response.json'));

        $this->assertEquals(
            json_decode($exampleResponse, true),
            $openApi->toArray()
        );

        $openApi->validate();
    }

        public function test_validate()
    {
        $exceptionThrown = false;

        try {
            $openApi = OpenApi::create()
                ->openapi('4.0.0')
                ->info(
                    Info::create()->title('foo')
                )
                ->paths(
                    PathItem::create()
                        ->route('/foo')
                        ->operations(
                            Operation::get()
                        )
                );

            $openApi->validate();
        }
        catch(ValidationException $exception) {
            $exceptionThrown = true;

            $this->assertCount(3, $exception->getErrors());
        }

        $this->assertTrue($exceptionThrown);
    }
}
