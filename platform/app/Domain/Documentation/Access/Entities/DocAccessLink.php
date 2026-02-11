<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Entities;

use App\Domain\Documentation\Access\Contracts\DocAccessLink as DocAccessLinkContract;
use App\Domain\Documentation\Access\ValueObjects\HashedToken;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DocAccessLink extends Model implements DocAccessLinkContract
{
    use HasUlids;

    protected $table = 'doc_access_links';

    /** @var list<string> */
    protected $fillable = [
        'project_id',
        'doc_role_id',
        'name',
        'token',
        'expires_at',
        'last_used_at',
        'ulid',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
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

    /** @return BelongsTo<DocRole, $this> */
    public function docRole(): BelongsTo
    {
        return $this->belongsTo(DocRole::class, 'doc_role_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUlid(): string
    {
        return $this->ulid;
    }

    public function getDocRoleUlid(): string
    {
        $this->loadMissing('docRole');

        return $this->docRole->ulid;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function getDocRoleId(): int
    {
        return $this->doc_role_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTokenHash(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): ?CarbonInterface
    {
        return $this->expires_at;
    }

    public function getLastUsedAt(): ?CarbonInterface
    {
        return $this->last_used_at;
    }

    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return ! $this->isExpired();
    }

    public function verifyToken(string $plainToken): bool
    {
        return (new HashedToken($this->token))->equals($plainToken);
    }
}
