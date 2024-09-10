<?php

namespace Tests\oooas\Integration;

use MohammadAlavi\ObjectOrientedOAS\Objects\AllOf;
use MohammadAlavi\ObjectOrientedOAS\Objects\Info;
use MohammadAlavi\ObjectOrientedOAS\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOAS\Objects\Operation;
use MohammadAlavi\ObjectOrientedOAS\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOAS\Objects\Response;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use MohammadAlavi\ObjectOrientedOAS\Objects\Tag;
use MohammadAlavi\ObjectOrientedOAS\OpenApi;
use PHPUnit\Framework\Attributes\CoversNothing;
use Tests\IntegrationTestCase;

#[CoversNothing]
class ReadmeTest extends IntegrationTestCase
{
        public function test_the_readme_example()
    {
        // Create a tag for all the user endpoints.
        $usersTag = Tag::create()
            ->name('Users')
            ->description('All user related endpoints');

        // Create the info section.
        $info = Info::create()
            ->title('API Specification')
            ->version('v1')
            ->description('For using the Example App API');

        // Create the user schema.
        $userSchema = Schema::object()
            ->properties(
                Schema::string('id')->format(Schema::FORMAT_UUID),
                Schema::string('name'),
                Schema::integer('age')->example(23),
                Schema::string('created_at')->format(Schema::FORMAT_DATE_TIME)
            );

        // Create the user response.
        $userResponse = Response::create()
            ->statusCode(200)
            ->description('OK')
            ->content(
                MediaType::json()->schema($userSchema)
            );

        // Create the operation for the route (i.e. GET, POST, etc.).
        $showUser = Operation::get()
            ->responses($userResponse)
            ->tags($usersTag)
            ->summary('Get an individual user')
            ->operationId('users.show');

        // Define the /users path along with the supported operations.
        $usersPath = PathItem::create()
            ->route('/users')
            ->operations($showUser);

        // Create the main OpenAPI object composed off everything created above.
        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_1)
            ->info($info)
            ->paths($usersPath)
            ->tags($usersTag);

        $readmeExample = file_get_contents(realpath(__DIR__ . '/../Stubs/readme_example.json'));

        $this->assertEquals(
            json_decode($readmeExample, true),
            $openApi->toArray()
        );
    }

        public function test_setting_and_unsetting_properties()
    {
        $info = Info::create()
            ->title('Example API');

        $openApi = OpenAPI::create()
            ->info($info);

        $this->assertEquals([
            'info' => [
                'title' => 'Example API',
            ],
        ], $openApi->toArray());

        $openApi = $openApi->info(null);

        $this->assertEquals([], $openApi->toArray());
    }

        public function test_unsetting_variadic_methods()
    {
        $path = PathItem::create()
            ->route('/users');

        $openApi = OpenAPI::create()
            ->paths($path);

        $this->assertEquals([
            'paths' => [
                '/users' => [],
            ],
        ], $openApi->toArray());

        $openApi = $openApi->paths();

        $this->assertEquals([], $openApi->toArray());
    }

        public function test_retrieving_properties()
    {
        $info = Info::create()->title('Example API');

        $this->assertEquals('Example API', $info->title);
    }

        public function test_object_id()
    {
        $schema = Schema::create()
            ->type(Schema::TYPE_OBJECT)
            ->properties(
                Schema::create('username')->type(Schema::TYPE_STRING),
                Schema::create('age')->type(Schema::TYPE_INTEGER)
            );

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                ],
                'age' => [
                    'type' => 'integer',
                ],
            ],
        ], $schema->toArray());
    }

        public function test_simpler_object_id()
    {
        $schema = Schema::object()
            ->properties(
                Schema::string('username'),
                Schema::integer('age')
            );

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                ],
                'age' => [
                    'type' => 'integer',
                ],
            ],
        ], $schema->toArray());
    }

        public function test_dollar_ref()
    {
        $schema = AllOf::create()
            ->schemas(
                Schema::ref('#/components/schemas/ExampleSchema')
            );

        $this->assertEquals([
            'allOf' => [
                ['$ref' => '#/components/schemas/ExampleSchema'],
            ],
        ], $schema->toArray());
    }
}
