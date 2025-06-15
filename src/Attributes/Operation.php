<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Collections\SecurityFactory;

#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class Operation
{
    /**
     * TODO: move this to docs
     * Security requirements can "AND" or "OR" together.
     * For example, [['BearerAuth', 'BasicAuth'], 'ApiKeyAuth', ['JWTAuth', BasicAuth]] translates to:
     * (BearerAuth AND BasicAuth) OR ApiKeyAuth OR (JWTAuth AND BasicAuth).
     * null -> use top level security
     * [] -> remove top level security
     * [[]] -> optional security.
     *
     * @param class-string<TagFactory>|array<array-key, class-string<TagFactory>>|null $tags
     * @param class-string<SecurityFactory>|null $security
     * @param class-string<ServerFactory>|array<array-key, class-string<ServerFactory>>|null $servers
     */
    public function __construct(
        public string|null       $operationId = null,
        public string|array|null $tags = null,
        public string|null       $security = null,
        public string|null       $method = null,
        public string|array|null $servers = null,
        public string|null       $summary = null,
        public string|null       $description = null,
        public bool|null         $deprecated = null,
    ) {
        // TODO: maybe we should validate class-strings like $security, $tags, $servers here?
        // If not here, then where?
        // Should we skip the validation and let the factory throw an exception?
        // When we try to generate the docs we will get an exception anyway.
    }
}
