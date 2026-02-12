<?php

use App\Domain\Documentation\Access\Contracts\DocRole;
use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;
use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;
use App\Domain\Documentation\Access\ValueObjects\ViewerContext;
use App\Domain\Documentation\Rendering\Services\SpecFilter;

function sampleSpec(): array
{
    return [
        'openapi' => '3.0.0',
        'info' => ['title' => 'Test API', 'version' => '1.0.0'],
        'tags' => [
            ['name' => 'payments', 'description' => 'Payment endpoints'],
            ['name' => 'users', 'description' => 'User endpoints'],
            ['name' => 'admin', 'description' => 'Admin endpoints'],
        ],
        'paths' => [
            '/api/payments' => [
                'get' => ['tags' => ['payments'], 'summary' => 'List payments'],
                'post' => ['tags' => ['payments'], 'summary' => 'Create payment'],
            ],
            '/api/users' => [
                'get' => ['tags' => ['users'], 'summary' => 'List users'],
            ],
            '/api/admin/settings' => [
                'get' => ['tags' => ['admin'], 'summary' => 'Get settings'],
            ],
        ],
    ];
}

function mockRule(RuleType $type, string $identifier, EndpointVisibility $visibility): DocVisibilityRule
{
    $rule = Mockery::mock(DocVisibilityRule::class);
    $rule->allows('getRuleType')->andReturn($type);
    $rule->allows('getIdentifier')->andReturn($identifier);
    $rule->allows('getVisibility')->andReturn($visibility);

    return $rule;
}

describe(class_basename(SpecFilter::class), function (): void {
    it('returns full spec for owner', function (): void {
        $filter = new SpecFilter();
        $spec = sampleSpec();

        $result = $filter->filter($spec, ViewerContext::owner(), []);

        expect($result)->toBe($spec);
    });

    it('keeps all endpoints when no rules defined (default public)', function (): void {
        $filter = new SpecFilter();

        $result = $filter->filter(sampleSpec(), ViewerContext::anonymous(), []);

        expect($result['paths'])->toHaveCount(3)
            ->and(array_keys($result['paths']))->toBe(['/api/payments', '/api/users', '/api/admin/settings']);
    });

    it('removes hidden endpoints for all non-owners', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Tag, 'admin', EndpointVisibility::Hidden)];

        $result = $filter->filter(sampleSpec(), ViewerContext::anonymous(), $rules);

        expect($result['paths'])->toHaveCount(2)
            ->and($result['paths'])->not->toHaveKey('/api/admin/settings');
    });

    it('removes internal endpoints for anonymous viewers', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Tag, 'users', EndpointVisibility::Internal)];

        $result = $filter->filter(sampleSpec(), ViewerContext::anonymous(), $rules);

        expect($result['paths'])->toHaveCount(2)
            ->and($result['paths'])->not->toHaveKey('/api/users');
    });

    it('keeps internal endpoints for role-holding viewers', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Tag, 'users', EndpointVisibility::Internal)];

        $role = Mockery::mock(DocRole::class);
        $role->allows('grantsAccessTo')->andReturn(false);

        $result = $filter->filter(sampleSpec(), ViewerContext::withRole($role), $rules);

        expect($result['paths'])->toHaveKey('/api/users');
    });

    it('removes restricted endpoints when role does not match', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Tag, 'payments', EndpointVisibility::Restricted)];

        $role = Mockery::mock(DocRole::class);
        $role->allows('grantsAccessTo')->andReturn(false);

        $result = $filter->filter(sampleSpec(), ViewerContext::withRole($role), $rules);

        expect($result['paths'])->not->toHaveKey('/api/payments');
    });

    it('keeps restricted endpoints when role matches tag', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Tag, 'payments', EndpointVisibility::Restricted)];

        $role = Mockery::mock(DocRole::class);
        $role->allows('grantsAccessTo')->with(RuleType::Path, Mockery::any())->andReturn(false);
        $role->allows('grantsAccessTo')->with(RuleType::Tag, 'payments')->andReturn(true);

        $result = $filter->filter(sampleSpec(), ViewerContext::withRole($role), $rules);

        expect($result['paths'])->toHaveKey('/api/payments');
    });

    it('keeps restricted endpoints when role matches path', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Path, '/api/payments', EndpointVisibility::Restricted)];

        $role = Mockery::mock(DocRole::class);
        $role->allows('grantsAccessTo')->with(RuleType::Path, '/api/payments')->andReturn(true);

        $result = $filter->filter(sampleSpec(), ViewerContext::withRole($role), $rules);

        expect($result['paths'])->toHaveKey('/api/payments');
    });

    it('path rules take priority over tag rules', function (): void {
        $filter = new SpecFilter();
        $rules = [
            mockRule(RuleType::Path, '/api/payments', EndpointVisibility::Public),
            mockRule(RuleType::Tag, 'payments', EndpointVisibility::Hidden),
        ];

        $result = $filter->filter(sampleSpec(), ViewerContext::anonymous(), $rules);

        expect($result['paths'])->toHaveKey('/api/payments');
    });

    it('supports wildcard patterns in rules', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Path, '/api/admin/*', EndpointVisibility::Hidden)];

        $result = $filter->filter(sampleSpec(), ViewerContext::anonymous(), $rules);

        expect($result['paths'])->not->toHaveKey('/api/admin/settings');
    });

    it('cleans orphaned tags after filtering', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Tag, 'admin', EndpointVisibility::Hidden)];

        $result = $filter->filter(sampleSpec(), ViewerContext::anonymous(), $rules);

        $tagNames = array_column($result['tags'], 'name');
        expect($tagNames)->toContain('payments')
            ->and($tagNames)->toContain('users')
            ->and($tagNames)->not->toContain('admin');
    });

    it('removes restricted endpoints for anonymous viewers', function (): void {
        $filter = new SpecFilter();
        $rules = [mockRule(RuleType::Tag, 'payments', EndpointVisibility::Restricted)];

        $result = $filter->filter(sampleSpec(), ViewerContext::anonymous(), $rules);

        expect($result['paths'])->not->toHaveKey('/api/payments');
    });

    it('removes entire path when all methods are filtered out', function (): void {
        $spec = [
            'paths' => [
                '/api/secret' => [
                    'get' => ['tags' => ['hidden-tag'], 'summary' => 'Secret'],
                ],
            ],
            'tags' => [['name' => 'hidden-tag']],
        ];
        $rules = [mockRule(RuleType::Tag, 'hidden-tag', EndpointVisibility::Hidden)];
        $filter = new SpecFilter();

        $result = $filter->filter($spec, ViewerContext::anonymous(), $rules);

        expect($result['paths'])->toBe([]);
    });

    it('handles spec with no paths', function (): void {
        $spec = ['openapi' => '3.0.0', 'info' => ['title' => 'Empty']];
        $filter = new SpecFilter();

        $result = $filter->filter($spec, ViewerContext::anonymous(), []);

        expect($result['paths'] ?? [])->toBe([]);
    });

    it('handles spec with no tags array', function (): void {
        $spec = [
            'paths' => [
                '/test' => ['get' => ['summary' => 'No tags']],
            ],
        ];
        $filter = new SpecFilter();

        $result = $filter->filter($spec, ViewerContext::anonymous(), []);

        expect($result['paths'])->toHaveKey('/test');
    });
})->covers(SpecFilter::class);
