<?php

namespace App\Domain\Documentation\Access\Entities;

use App\Domain\Documentation\Access\Contracts\DocRole as DocRoleContract;
use App\Domain\Documentation\Access\Enums\RuleType;
use App\Domain\Documentation\Access\ValueObjects\ScopeCollection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DocRole extends Model implements DocRoleContract
{
    use HasUlids;

    protected $table = 'doc_roles';

    /** @var list<string> */
    protected $fillable = [
        'project_id',
        'name',
        'scopes',
        'is_default',
        'ulid',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'scopes' => 'array',
            'is_default' => 'boolean',
        ];
    }

    /** @return list<string> */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<\App\Models\Project, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Project::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUlid(): string
    {
        return $this->ulid;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScopes(): ScopeCollection
    {
        return ScopeCollection::fromArray($this->scopes ?? []);
    }

    public function isDefault(): bool
    {
        return $this->is_default;
    }

    public function grantsAccessTo(RuleType $ruleType, string $identifier): bool
    {
        return $this->getScopes()->matchesAny($identifier);
    }
}
