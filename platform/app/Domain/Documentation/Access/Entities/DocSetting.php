<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Entities;

use App\Domain\Documentation\Access\Contracts\DocSetting as DocSettingContract;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DocSetting extends Model implements DocSettingContract
{
    protected $table = 'doc_settings';

    /** @var list<string> */
    protected $fillable = [
        'project_id',
        'visibility',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'visibility' => DocVisibility::class,
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

    public function getVisibility(): DocVisibility
    {
        return $this->visibility;
    }

    public function isPublic(): bool
    {
        return $this->visibility === DocVisibility::Public;
    }

    public function isPrivate(): bool
    {
        return $this->visibility === DocVisibility::Private;
    }
}
