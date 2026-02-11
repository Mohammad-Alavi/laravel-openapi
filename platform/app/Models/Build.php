<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BuildStatus;
use Database\Factories\BuildFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Build extends Model
{
    /** @use HasFactory<BuildFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'commit_sha',
        'status',
        'output_path',
        'error_log',
        'started_at',
        'completed_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => BuildStatus::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Project, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
