<?php

namespace MohammadAlavi\LaravelOpenApi\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Operation as ParentOperation;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement;
use MohammadAlavi\LaravelOpenApi\SecuritySchemes\DefaultSecurityScheme;
use MohammadAlavi\LaravelOpenApi\SecuritySchemes\PublicSecurityScheme;

class Operation extends ParentOperation
{
    /**
     * You should only send one security requirement per operation.
     * If you send more than one, the first one will be used.
     */
    public function security(SecurityRequirement ...$security): self
    {
        $instance = clone $this;

        // true overrides "global security" = [] in the generated OpenAPI spec
        // false/null uses $security
        // we disable it and use $security to configure the security.
        $instance = $instance->noSecurity(false);

        if ($this->shouldUseGlobalSecurity($security[0])) {
            $instance->security = null;

            return $instance;
        }

        if ($this->isPublic($security[0])) {
            $instance->security = [];

            return $instance;
        }

        $instance->security = $security[0];

        return $instance;
    }

    private function shouldUseGlobalSecurity(SecurityRequirement $security): bool
    {
        return DefaultSecurityScheme::NAME === $security->securityScheme;
    }

    private function isPublic(SecurityRequirement $security): bool
    {
        return PublicSecurityScheme::NAME === $security->securityScheme;
    }
}
