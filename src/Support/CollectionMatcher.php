<?php

namespace MohammadAlavi\LaravelOpenApi\Support;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection as CollectionAttribute;

/**
 * Determines if a route belongs to a specific OpenAPI collection.
 *
 * Handles the logic for matching routes to collections based on
 * controller and action-level Collection attributes.
 */
final readonly class CollectionMatcher
{
    public function __construct(
        private RouteInfo $routeInfo,
    ) {
    }

    /**
     * Check if the route belongs to the specified collection.
     *
     * When the action has a Collection attribute and config
     * 'openapi.collection.action_attribute_overrides_controller_attribute' is true (default),
     * only the action's collection is checked. Otherwise, both are checked.
     */
    public function isInCollection(string $collection): bool
    {
        $actionCollectionAttr = $this->getActionCollectionAttribute();
        if (
            !is_null($actionCollectionAttr)
            && config()->boolean('openapi.collection.action_attribute_overrides_controller_attribute', true)
        ) {
            return in_array(
                $collection,
                $actionCollectionAttr->name,
                true,
            );
        }

        return in_array(
            $collection,
            $this->getControllerCollectionAttribute()?->name ?? [],
            true,
        ) || in_array(
            $collection,
            $actionCollectionAttr?->name ?? [],
            true,
        );
    }

    /**
     * Check if the route has any Collection attribute.
     */
    public function hasCollectionAttribute(): bool
    {
        return $this->getCollectionAttributes()->isNotEmpty();
    }

    /**
     * Get all Collection attributes from both controller and action.
     */
    public function getCollectionAttributes(): Collection
    {
        return $this->routeInfo->controllerAttributes()
            ->merge($this->routeInfo->actionAttributes())
            ->filter(
                static function (object $attribute): bool {
                    return $attribute instanceof CollectionAttribute;
                },
            );
    }

    private function getControllerCollectionAttribute(): CollectionAttribute|null
    {
        return $this->routeInfo->controllerAttributes()
            ->first(
                static function (object $attribute): bool {
                    return $attribute instanceof CollectionAttribute;
                },
            );
    }

    private function getActionCollectionAttribute(): CollectionAttribute|null
    {
        return $this->routeInfo->actionAttributes()
            ->first(
                static function (object $attribute): bool {
                    return $attribute instanceof CollectionAttribute;
                },
            );
    }
}
