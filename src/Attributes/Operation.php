<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;
use MohammadAlavi\LaravelOpenApi\Factories\ServerFactory;
use MohammadAlavi\LaravelOpenApi\Factories\TagFactory;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Operation
{
    private const REMOVE_TOP_LEVEL_SECURITY = [];
    private const USE_TOP_LEVEL_SECURITY = null;

    /**
     * @param class-string<TagFactory>|array<array-key, class-string<TagFactory>>|null $tags
     * @param class-string<SecuritySchemeFactory>|array<array-key, class-string<SecuritySchemeFactory>>|array<array-key, array<array-key, class-string<SecuritySchemeFactory>>>|null $security
     *                                                                                                                                                                                         TODO: move this to docs
     *                                                                                                                                                                                         Security requirements can "AND" or "OR" together.
     *                                                                                                                                                                                         For example, [['BearerAuth', 'BasicAuth'], 'ApiKeyAuth', ['JWTAuth', BasicAuth]] translates to:
     *                                                                                                                                                                                         (BearerAuth AND BasicAuth) OR ApiKeyAuth OR (JWTAuth AND BasicAuth).
     *
     * null -> use top level security
     * [] -> remove top level security
     * [[]] -> optional security
     * @param class-string<ServerFactory>|array<array-key, class-string<ServerFactory>>|null $servers
     */
    public function __construct(
        public string|null $id = null,
        public string|array|null $tags = null,
        public string|array|null $security = null,
        public string|null $method = null,
        public string|array|null $servers = null,
        public string|null $summary = null,
        public string|null $description = null,
        public bool|null $deprecated = null,
    ) {
        if (!$this->isValidSecurityScheme($security)) {
            throw new \InvalidArgumentException(sprintf('Security class is either not declared or is not an instance of %s.', SecuritySchemeFactory::class));
        }
    }

    private function isValidSecurityScheme(string|array|null $security): bool
    {
        if (static::REMOVE_TOP_LEVEL_SECURITY === $security || static::USE_TOP_LEVEL_SECURITY === $security) {
            return true;
        }

        if (is_string($security)) {
            return $this->isValidSingleSecurityScheme($security);
        }

        if (is_array($security)) {
            return $this->isValidMultiSecurityScheme($security);
        }

        return false;
    }

    private function isValidSingleSecurityScheme(string|null $securityScheme): bool
    {
        return !is_null($securityScheme) && '' !== $securityScheme && class_exists($securityScheme) && is_a($securityScheme, SecuritySchemeFactory::class, true);
    }

    private function isValidMultiSecurityScheme(array $securities): bool
    {
        $isValid = true;
        foreach ($securities as $security) {
            if (is_array($security)) {
                if (0 === count($security)) {
                    return false;
                }

                return $this->isValidMultiSecurityScheme($security);
            }

            $isValid = $isValid && $this->isValidSingleSecurityScheme($security);
        }

        return $isValid;
    }
}
