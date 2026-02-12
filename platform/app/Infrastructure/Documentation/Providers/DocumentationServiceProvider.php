<?php

namespace App\Infrastructure\Documentation\Providers;

use App\Domain\Documentation\Access\Repositories\DocAccessLinkRepository;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;
use App\Domain\Documentation\Access\Repositories\DocSettingRepository;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;
use App\Infrastructure\Documentation\Repositories\EloquentDocAccessLinkRepository;
use App\Infrastructure\Documentation\Repositories\EloquentDocRoleRepository;
use App\Infrastructure\Documentation\Repositories\EloquentDocSettingRepository;
use App\Infrastructure\Documentation\Repositories\EloquentDocVisibilityRuleRepository;
use Illuminate\Support\ServiceProvider;

final class DocumentationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DocSettingRepository::class, EloquentDocSettingRepository::class);
        $this->app->bind(DocRoleRepository::class, EloquentDocRoleRepository::class);
        $this->app->bind(DocVisibilityRuleRepository::class, EloquentDocVisibilityRuleRepository::class);
        $this->app->bind(DocAccessLinkRepository::class, EloquentDocAccessLinkRepository::class);
    }
}
