<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Entities;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule as DocVisibilityRuleContract;
use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DocVisibilityRule extends Model implements DocVisibilityRuleContract
{
    protected $table = 'doc_visibility_rules';

    /** @var list<string> */
    protected $fillable = [
        'project_id',
        'rule_type',
        'identifier',
        'visibility',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'rule_type' => RuleType::class,
            'visibility' => EndpointVisibility::class,
        ];
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

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function getRuleType(): RuleType
    {
        return $this->rule_type;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getVisibility(): EndpointVisibility
    {
        return $this->visibility;
    }
}
