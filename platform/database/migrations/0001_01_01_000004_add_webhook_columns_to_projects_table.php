<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->unsignedBigInteger('github_webhook_id')->nullable()->after('github_branch');
            $table->text('github_webhook_secret')->nullable()->after('github_webhook_id');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn(['github_webhook_id', 'github_webhook_secret']);
        });
    }
};
