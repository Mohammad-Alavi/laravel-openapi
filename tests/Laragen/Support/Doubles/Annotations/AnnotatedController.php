<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Annotations;

final class AnnotatedController
{
    /**
     * @response 200 {"id": 1, "name": "John", "is_active": true}
     */
    public function withResponse(): void
    {
    }

    /**
     * @response {"id": 1, "name": "John"}
     */
    public function withResponseNoStatus(): void
    {
    }

    /**
     * @response 200 {"id": 1, "name": "John"}
     * @response 404 {"error": "Not found"}
     */
    public function withMultipleResponses(): void
    {
    }

    /**
     * @bodyParam name string required The user's name
     * @bodyParam age integer
     * @bodyParam is_active boolean required
     */
    public function withBodyParams(): void
    {
    }

    /**
     * @queryParam page integer The page number
     * @queryParam per_page integer
     * @queryParam search string The search term
     */
    public function withQueryParams(): void
    {
    }

    /**
     * @response 200 {"id": 1}
     *
     * @bodyParam title string required
     *
     * @queryParam include string
     */
    public function withMixedAnnotations(): void
    {
    }

    public function withoutAnnotations(): void
    {
    }

    /**
     * Just a regular docblock with no annotations.
     */
    public function withRegularDocblock(): void
    {
    }
}
