<?php

namespace Workbench\App\Documentation;

use MohammadAlavi\LaravelOpenApi\Factories\OpenAPIFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use Workbench\App\Documentation\WorkbenchSecurity;

final readonly class Workbench extends OpenAPIFactory
{
    public function instance(): OpenAPI
    {
        return OpenAPI::v311(
            Info::create(
                'https://laragen.io',
                '1.0.3',
            )->summary('Default OpenAPI Specification')
                ->description(
                    'This is the default OpenAPI specification for the application.',
                )->contact(
                    Contact::create()
                        ->name('Example Contact')
                        ->email('example@example.com')
                        ->url('https://example.com/'),
                )->license(
                    License::create('MIT')
                        ->url('https://github.com/'),
                ),
        )->servers(
            Server::create('https://laragen.io'),
        )->security((new WorkbenchSecurity())->build());
    }
}
