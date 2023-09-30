<?php

namespace Vyuldashev\LaravelOpenApi\Objects;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi as ParentOpenApi;
use Vyuldashev\LaravelOpenApi\Builders\SecurityRequirementBuilder;
use Vyuldashev\LaravelOpenApi\SecuritySchemes\PublicSecurityScheme;

class OpenApi extends ParentOpenApi
{
    // This is just a wrapper around parent class security()
    // to allow for multiple security requirements
    public function multiAuthSecurity(array $security): self
    {
        $securityRequirements = app(SecurityRequirementBuilder::class)->build($security);

        return $this->security($securityRequirements);
    }

    /**
     * You should only send one security requirement per operation.
     * If you send more than one, the first one will be used.
     */
    public function security(SecurityRequirement ...$security): self
    {
        $instance = clone $this;

        if (empty($security)) {
            $instance->security = null;

            return $instance;
        }

        if ($this->hasNoGlobalSecurity($security[0])) {
            $instance->security = null;

            return $instance;
        }

        $instance->security = $security[0];

        return $instance;
    }

    private function hasNoGlobalSecurity(SecurityRequirement $security): bool
    {
        return $security->securityScheme === PublicSecurityScheme::NAME;
    }
}
