<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\Auth;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

final class BearerSecuritySchemeFactory extends SecuritySchemeFactory
{
    public static function name(): string
    {
        return 'BearerAuth';
    }

    public function component(): SecurityScheme
    {
        return SecurityScheme::http(Http::bearer());
    }
}
