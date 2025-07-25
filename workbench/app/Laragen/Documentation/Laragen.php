<?php

namespace Workbench\App\Laragen\Documentation;

use MohammadAlavi\LaravelOpenApi\Factories\OpenAPIFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use Workbench\App\Documentation\WorkbenchSecurity;

final readonly class Laragen extends OpenAPIFactory
{
    public function instance(): OpenAPI
    {
        return OpenAPI::v311(
            Info::create(
                'https://laragen.io',
                '1.0.0',
            )->summary('Laragen OpenAPI Specification')
                ->description(
                    'Laragen is a Laravel package that automatically generates OpenAPI documentation for your Laravel application. It analyzes your routes, controllers, and request validation rules to create a comprehensive OpenAPI specification.',
                )->contact(
                    Contact::create()
                        ->name('Mohammad Alavi')
                        ->email('mohammad.alavi1990@gmail.com')
                        ->url('https://alavi.dev/'),
                )->license(
                    License::create('MIT')
                        ->url('https://github.com/'),
                ),
        )->servers(
            Server::create('https://laragen.io'),
        )->security((new WorkbenchSecurity())->build());
    }
}
