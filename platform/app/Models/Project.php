<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

final class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;
    use HasUlids;

    /** @return list<string> */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @var list<string> */
    protected $hidden = [
        'github_webhook_id',
        'github_webhook_secret',
    ];

    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'github_repo_url',
        'github_branch',
        'github_webhook_id',
        'github_webhook_secret',
        'status',
        'last_built_at',
        'latest_build_id',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'last_built_at' => 'datetime',
            'github_webhook_secret' => 'encrypted',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Project $project): void {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Build, $this> */
    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }

    /** @return BelongsTo<Build, $this> */
    public function latestBuild(): BelongsTo
    {
        return $this->belongsTo(Build::class, 'latest_build_id');
    }
}
