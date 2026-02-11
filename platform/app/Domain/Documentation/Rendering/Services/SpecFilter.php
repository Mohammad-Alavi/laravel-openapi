<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Rendering\Services;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;
use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;
use App\Domain\Documentation\Access\ValueObjects\Scope;
use App\Domain\Documentation\Access\ValueObjects\ViewerContext;

final class SpecFilter
{
    /**
     * Filter an OpenAPI spec array based on viewer context and visibility rules.
     *
     * @param array<string, mixed> $spec
     * @param list<DocVisibilityRule> $rules
     * @return array<string, mixed>
     */
    public function filter(array $spec, ViewerContext $viewer, array $rules): array
    {
        if ($viewer->isOwner()) {
            return $spec;
        }

        $paths = $spec['paths'] ?? [];
        $filteredPaths = [];

        foreach ($paths as $path => $methods) {
            $filteredMethods = [];

            foreach ($methods as $method => $operation) {
                if (! is_array($operation)) {
                    $filteredMethods[$method] = $operation;

                    continue;
                }

                $visibility = $this->resolveVisibility($path, $operation, $rules);

                if ($this->shouldInclude($visibility, $viewer, $path, $operation)) {
                    $filteredMethods[$method] = $operation;
                }
            }

            if ($filteredMethods !== []) {
                $filteredPaths[$path] = $filteredMethods;
            }
        }

        $spec['paths'] = $filteredPaths;
        $spec = $this->cleanOrphanedTags($spec);

        return $spec;
    }

    /**
     * @param array<string, mixed> $operation
     * @param list<DocVisibilityRule> $rules
     */
    private function resolveVisibility(string $path, array $operation, array $rules): EndpointVisibility
    {
        // Path rules take priority over tag rules
        foreach ($rules as $rule) {
            if ($rule->getRuleType() === RuleType::Path) {
                $scope = new Scope($rule->getIdentifier());
                if ($scope->matches($path)) {
                    return $rule->getVisibility();
                }
            }
        }

        // Check tag rules
        $tags = $operation['tags'] ?? [];
        foreach ($tags as $tag) {
            foreach ($rules as $rule) {
                if ($rule->getRuleType() === RuleType::Tag) {
                    $scope = new Scope($rule->getIdentifier());
                    if ($scope->matches($tag)) {
                        return $rule->getVisibility();
                    }
                }
            }
        }

        return EndpointVisibility::Public;
    }

    /** @param array<string, mixed> $operation */
    private function shouldInclude(EndpointVisibility $visibility, ViewerContext $viewer, string $path, array $operation): bool
    {
        return match ($visibility) {
            EndpointVisibility::Public => true,
            EndpointVisibility::Internal => ! $viewer->isAnonymous(),
            EndpointVisibility::Restricted => $viewer->hasRole() && $this->roleMatchesOperation($viewer, $path, $operation),
            EndpointVisibility::Hidden => false,
        };
    }

    /** @param array<string, mixed> $operation */
    private function roleMatchesOperation(ViewerContext $viewer, string $path, array $operation): bool
    {
        $role = $viewer->role();
        if ($role === null) {
            return false;
        }

        // Check if role grants access to the path
        if ($role->grantsAccessTo(RuleType::Path, $path)) {
            return true;
        }

        // Check if role grants access to any of the operation's tags
        foreach ($operation['tags'] ?? [] as $tag) {
            if ($role->grantsAccessTo(RuleType::Tag, $tag)) {
                return true;
            }
        }

        return false;
    }

    /** @param array<string, mixed> $spec @return array<string, mixed> */
    private function cleanOrphanedTags(array $spec): array
    {
        if (! isset($spec['tags']) || ! is_array($spec['tags'])) {
            return $spec;
        }

        $usedTags = [];
        foreach ($spec['paths'] ?? [] as $methods) {
            foreach ($methods as $operation) {
                if (is_array($operation)) {
                    foreach ($operation['tags'] ?? [] as $tag) {
                        $usedTags[$tag] = true;
                    }
                }
            }
        }

        $spec['tags'] = array_values(array_filter(
            $spec['tags'],
            static fn (array $tag): bool => isset($usedTags[$tag['name'] ?? '']),
        ));

        return $spec;
    }
}
