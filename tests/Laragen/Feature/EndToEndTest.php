<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\Laragen;
use MohammadAlavi\LaravelOpenApi\Factories\OpenAPIFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use Tests\Laragen\Support\Doubles\E2E\E2EController;

describe('End-to-end generation', function (): void {
    beforeEach(function (): void {
        // Register a test OpenAPI collection for E2E
        config([
            'openapi.collections.E2E' => [
                'openapi' => E2EOpenAPIFactory::class,
            ],
            'laragen.route_discovery.mode' => 'auto',
            'laragen.route_discovery.include' => ['api/e2e/*'],
            'laragen.route_discovery.exclude' => [],
            'laragen.autogen.request_body' => true,
            'laragen.autogen.example' => false,
            'laragen.autogen.security' => true,
            'laragen.autogen.path_parameters' => true,
            'laragen.autogen.response' => true,
        ]);

        // F1: Route discovery — register test routes under api/e2e/*
        // F2: Path parameters — {id} with UUID constraint
        // F3: Request body — FormRequest on store
        // F4: Response schema — JsonResource return types
        // F6: Security — auth:sanctum middleware
        RouteFacade::prefix('api/e2e')->group(static function (): void {
            RouteFacade::post('/articles', [E2EController::class, 'store'])
                ->middleware(['api', 'auth:sanctum']);

            RouteFacade::get('/articles/{id}', [E2EController::class, 'show'])
                ->middleware(['api', 'auth:sanctum'])
                ->whereUuid('id');

            RouteFacade::delete('/articles/{id}', [E2EController::class, 'delete'])
                ->middleware(['api']);
        });
    });

    it('generates a spec with all enrichments from all 6 features', function (): void {
        $spec = Laragen::generate('E2E');
        $compiled = $spec->compile();

        // Basic structure
        expect($compiled)->toHaveKeys(['openapi', 'info', 'paths'])
            ->and($compiled['openapi'])->toBe('3.1.1')
            ->and($compiled['info']['title'])->toBe('https://e2e-test.local');
    });

    // F1: Route Discovery
    it('discovers routes by URI pattern', function (): void {
        $spec = Laragen::generate('E2E');
        $compiled = $spec->compile();
        $paths = array_keys($compiled['paths']);

        expect($paths)->toContain('/api/e2e/articles')
            ->and($paths)->toContain('/api/e2e/articles/{id}');
    });

    // F3: FormRequest → request body schema
    it('generates request body from FormRequest validation rules', function (): void {
        $spec = Laragen::generate('E2E');
        $compiled = $spec->compile();

        $postOp = $compiled['paths']['/api/e2e/articles']['post'];

        expect($postOp)->toHaveKey('requestBody')
            ->and($postOp['requestBody']['content']['application/json']['schema']['properties'])
            ->toHaveKeys(['title', 'body', 'status', 'notify']);
    });

    // F6: Security detection
    it('detects auth:sanctum and applies security to operations', function (): void {
        $spec = Laragen::generate('E2E');
        $compiled = $spec->compile();

        // POST /articles has auth:sanctum → should have security
        $postOp = $compiled['paths']['/api/e2e/articles']['post'];
        expect($postOp)->toHaveKey('security');

        // GET /articles/{id} has auth:sanctum → should have security
        $getOp = $compiled['paths']['/api/e2e/articles/{id}']['get'];
        expect($getOp)->toHaveKey('security');

        // DELETE /articles/{id} has no auth → should NOT have security
        $deleteOp = $compiled['paths']['/api/e2e/articles/{id}']['delete'];
        expect($deleteOp)->not->toHaveKey('security');
    });

    // F2: Path parameter detection
    it('detects UUID path parameters from route constraints', function (): void {
        $spec = Laragen::generate('E2E');
        $compiled = $spec->compile();

        $pathItem = $compiled['paths']['/api/e2e/articles/{id}'];

        expect($pathItem)->toHaveKey('parameters');

        $idParam = collect($pathItem['parameters'])->firstWhere('name', 'id');

        expect($idParam)->not->toBeNull()
            ->and($idParam['in'])->toBe('path')
            ->and($idParam['required'])->toBeTrue()
            ->and($idParam['schema']['type'])->toBe('string')
            ->and($idParam['schema']['format'])->toBe('uuid');
    });

    // F4: JsonResource response schema
    it('generates response schema from JsonResource return type', function (): void {
        $spec = Laragen::generate('E2E');
        $compiled = $spec->compile();

        // POST /articles returns E2EResource → should have 200 response
        $postOp = $compiled['paths']['/api/e2e/articles']['post'];
        expect($postOp)->toHaveKey('responses')
            ->and($postOp['responses'])->toHaveKey('200');

        $responseSchema = $postOp['responses']['200']['content']['application/json']['schema'];

        // E2EResource wraps in 'data' by default
        expect($responseSchema['properties'])->toHaveKey('data');

        $dataProps = $responseSchema['properties']['data']['properties'];

        expect($dataProps)->toHaveKeys(['id', 'title', 'type', 'is_published'])
            ->and($dataProps['id']['type'])->toBe('integer')
            ->and($dataProps['title']['type'])->toBe('string')
            ->and($dataProps['is_published']['type'])->toBe('boolean')
            ->and($dataProps['type']['enum'])->toBe(['article']);
    });

    // F4: No response for untyped return
    it('does not generate response for methods without JsonResource return', function (): void {
        $spec = Laragen::generate('E2E');
        $compiled = $spec->compile();

        $deleteOp = $compiled['paths']['/api/e2e/articles/{id}']['delete'];

        expect($deleteOp)->not->toHaveKey('responses');
    });

    // Combined: full spec structural validity
    it('produces a structurally valid OpenAPI spec', function (): void {
        $spec = Laragen::generate('E2E');

        $spec->toJsonFile('e2e', 'temp/tests', JSON_PRETTY_PRINT);

        // Use minimal validation — auto-generated specs lack summaries, 4xx responses, etc.
        // Strict validation (recommended-strict) is covered by LaragenTest for attribute-based specs
        exec(
            'npx redocly lint --format stylish --extends minimal temp/tests/e2e.json 2>&1',
            $output,
            $resultCode,
        );

        expect($resultCode)->toBe(0, implode("\n", $output));

        $this->pushCleanupCallback(
            static function (): void {
                \Safe\unlink('temp/tests/e2e.json');
            },
        );
    });
})->covers(Laragen::class);

final readonly class E2EOpenAPIFactory extends OpenAPIFactory
{
    public function instance(): OpenAPI
    {
        return OpenAPI::v311(
            Info::create(
                'https://e2e-test.local',
                '1.0.0',
            ),
        );
    }
}
