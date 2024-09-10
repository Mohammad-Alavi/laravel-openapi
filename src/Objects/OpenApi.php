<?php

namespace MohammadAlavi\LaravelOpenApi\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOAS\OpenApi as ParentOpenApi;
use MohammadAlavi\LaravelOpenApi\Collectors\SecurityRequirementBuilder;
use MohammadAlavi\LaravelOpenApi\SecuritySchemes\PublicSecurityScheme;

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
        return PublicSecurityScheme::NAME === $security->securityScheme;
    }
}
